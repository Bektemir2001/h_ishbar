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
    protected int|null $user;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function saveData(DataRequest $request)
    {
        $data = $request->validated();
        $result = $this->userService->saveData($data, config('app.user'));
        if($result['code'] == 200)
        {
            return response(['data' => [
                "user" => $result['user'],
                "user_data" => $result['user_data'],
                "tags" => $result['user']->tags
            ]]);
        }
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }

    public function getData()
    {
        $user = User::where('id', config('app.user'))->first();
        return response(['data' => [
            "user" => $user,
            "user_data" => $user->data,
            "tags" => $user->tags
        ]]);

    }
}
