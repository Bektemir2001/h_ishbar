<?php

namespace App\Services;

use App\Models\User;
use App\Models\Work;

class WorkService
{
    public function saveWork(array $data, User $user)
    {
        try {
            $data['employer_id'] = $user->id;
            Work::create($data);
            return ['message' => 'success', 'code' => 200];
        }
        catch (\Exception $exception)
        {
            return ['message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }
}
