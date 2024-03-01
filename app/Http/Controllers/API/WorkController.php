<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Work\WorkRequest;
use App\Models\User;
use App\Services\WorkService;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    protected WorkService $workService;

    public function __construct(WorkService $workService)
    {
        $this->workService = $workService;
    }

    public function createWork(WorkRequest $request, User $user)
    {
        $data = $request->validated();
        $result = $this->workService->saveWork($data, $user);
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }
}
