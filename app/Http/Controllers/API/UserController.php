<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\DataRequest;
use App\Models\User;
use App\Models\UserData;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;
    protected int $user;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->user = config('app.user');
    }

    public function saveData(DataRequest $request)
    {
        $data = $request->validated();
        $result = $this->userService->saveData($data, $this->user);
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }

    public function getData()
    {
        return response([
            'data' => ['user' => User::where('id', $this->user)->first(),
                       'data' => UserData::where('user_id', $this->user)->first()
            ]]);

    }
}
