<?php

namespace App\Services;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkStatement;

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
}
