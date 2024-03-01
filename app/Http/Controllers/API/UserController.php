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
        dd($data);
        $result = $this->userService->saveData($data, config('app.user'));
        if($result['code'] == 200)
        {
            return response(['data' => $result]);
        }
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }

    public function getData()
    {
        return response([
            'data' => ['user' => User::where('id', config('app.user'))->first(),
                       'data' => UserData::where('user_id', config('app.user'))->first()
            ]]);

    }
}
