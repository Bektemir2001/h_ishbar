<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserData;

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
            $data['user_id'] = $user;
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
            return ['user' => User::where('id', $user)->first(), 'user_data' => $user_data, 'code' => 200];
        }
        catch (\Exception $exception)
        {
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }
}
