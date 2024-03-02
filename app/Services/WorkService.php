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

    public function statement(Work $work, User $user)
    {
        try {
            WorkStatement::create(['work_id' => $work, 'user_id' => $user->id]);
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
                if(isset($filter['price']))
                {
                    $data = Work::where('price', '>=', $filter['price']);
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
                    $x = $filter['x'];
                    $y = $filter['y'];
                    $data = $data->selectRaw("
                            *,
                            (6371 * acos(cos(radians(CAST($x AS DECIMAL(10, 6)))) * cos(radians(x)) * cos(radians(y) - radians(CAST($y AS DECIMAL(10, 6)))) + sin(radians(CAST($x AS DECIMAL(10, 6)))) * sin(radians(x)))) AS distance
                        ")
                        ->having('distance', '<=', $radius);
                }
                return ['data' => $data->get(), 'code' => 200];

            }
            else{
                return ['data' => Work::all(), 'code' => 200];
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
