<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected UploadFileService $uploadFileService;

    public function __construct(UploadFileService $uploadFileService)
    {
        $this->uploadFileService = $uploadFileService;
    }
    public function saveData(array $data, int $user): array
    {
        try {
            DB::beginTransaction();
            $data['user_id'] = $user;
            $tags = isset($data['tags']) ? $this->tagsHandle($data['tags']) : [];
            unset($data['tags']);
            $user_data = UserData::where('user_id', $user)->first();
            if(isset($data['image']))
            {
                $data['image'] = $this->uploadFileService->upload($data['image'], 'images');
            }
            if(!$user_data)
            {
                $user_data = UserData::create($data);
            }
            else{
                $user_data->update($data);
            }
            $user = User::where('id', $user)->first();
            if (!empty($tags)) {
                if($user->tags)
                {
                    $user->tags()->sync($tags);
                }
                else{
                    $user->tags()->attach($tags);
                }

            }
            DB::commit();
            return ['user' => $user, 'user_data' => $user_data, 'code' => 200];
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
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
