<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserData;

class UserService
{
    public function saveData(array $data, int $user): array
    {
        try {
            $data['user_id'] = $user;
            $user_data = UserData::where('user_id', $user)->first();
            if(!$user_data)
            {
                UserData::create($data);
            }
            else{
                $user_data->update($data);
            }
            return ['message' => 'success', 'code' => 200];
        }
        catch (\Exception $exception)
        {
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }
}
