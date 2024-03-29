<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkStatement;
use Illuminate\Support\Facades\DB;

class WorkService
{
    public function saveWork(array $data, int $user)
    {
        try {
            DB::beginTransaction();
            $data['employer_id'] = $user;
            $tags = isset($data['tags']) ? $this->tagsHandle($data['tags']) : [];
            unset($data['tags']);
            $work = Work::create($data);
            if (!empty($tags)) {
                $work->tags()->attach($tags);
            }
            DB::commit();
            return ['message' => 'success', 'work' => $work, 'code' => 200];
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    public function statement(Work $work, int $user)
    {
        try {
            WorkStatement::create(['work_id' => $work->id, 'user_id' => $user]);
            return ['message' => 'success', 'code' => 200];
        }
        catch (\Exception $exception)
        {
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    public function getWorks(array $filter, int $user)
    {
        try {

            if(count($filter))
            {
                $data = Work::query();
                if(isset($filter['from_price']))
                {
                    $data = Work::where('price', '>=', $filter['from_price']);
                }
                if(isset($filter['to_price']))
                {
                    $data = Work::where('price', '<=', $filter['to_price']);
                }
                if(isset($filter['categories']))
                {
                    if(count($filter['categories']))
                    {
                        $data = $data->whereIn('category_id', $filter['categories']);
                    }
                }
                if(isset($filter['tags']))
                {
                    $tags = $filter['tags'];
                    if(count($tags))
                    {
                        $data = $data->whereHas('tags', function ($query) use ($tags) {
                            $query->whereIn('tag_id', $tags);
                        });
                    }
                }
                if($filter['radius'])
                {
                    $radius = $filter['radius'];
                    $x = floatval($filter['x']);
                    $y = floatval($filter['y']);
                    $data = $data->addSelect([
                        '*',
                        DB::raw("(6371 * acos(cos(radians($x)) * cos(radians(CAST(`x` AS DECIMAL(10, 6)))) * cos(radians(CAST(`y` AS DECIMAL(10, 6))) - radians($y)) + sin(radians($x)) * sin(radians(CAST(`x` AS DECIMAL(10, 6)))))) AS distance")
                    ])
                        ->having('distance', '<=', $radius);
                }
                return ['data' => $data->where('status', 0)->get(), 'code' => 200];

            }
            else{
                return ['data' => Work::where('status', 0), 'code' => 200];
            }

        }
        catch (\Exception $exception)
        {
            return ['data' => $exception->getMessage(), 'code' => $exception->getCode()];
        }

    }

    public function isNumericString($str) {
        return ctype_digit($str);
    }

    public function tagsHandle($tags)
    {
        if(is_string($tags))
        {
            $tags = explode(',', $tags);
        }
        for($i = 0; $i < count($tags); $i++)
        {
            if(!$this->isNumericString($tags[$i]))
            {
                $new_tag = Tag::firstOrCreate(['name' => $tags[$i]]);
                $tags[$i] = $new_tag->id;
            }
        }
        return $tags;
    }
}
