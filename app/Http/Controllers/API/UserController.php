<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\DataRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function saveData(DataRequest $request, User $user)
    {
        $data = $request->validated();
        $result = $this->userService->saveData($data, $user);
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }
}
