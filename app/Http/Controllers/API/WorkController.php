<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Work\FilterRequest;
use App\Http\Requests\Api\Work\WorkRequest;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkStatement;
use App\Services\WorkService;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    protected WorkService $workService;

    public function __construct(WorkService $workService)
    {
        $this->workService = $workService;
    }


    public function getWorks(FilterRequest $request)
    {
        $filter = $request->validated();
        $result = $this->workService->getWorks($filter, config('app.user'));
        return response(['data' => $result['data']])->setStatusCode($result['code']);
    }
    public function createWork(WorkRequest $request)
    {
        $data = $request->validated();
        $result = $this->workService->saveWork($data, config('app.user'));
        if($result['code'] == 200)
        {
            return response(['data' => $result['work']]);
        }
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }

    public function workStatement(Work $work)
    {
        $result = $this->workService->statement($work, config('app.user'));
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }


}
