<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required', 'password' => 'nullable']);
        $user = User::where('email',$data['email'])->first();
        if(!$user){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        return response()->json([
            'user_id' => $user->id
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate(['name' => 'required', 'email' => 'password', 'password' => 'required']);
        try {
            $user = User::create($data);
            return response(['user_id' => $user->id]);
        }
        catch (\Exception $e)
        {
            return response(['message' => $e->getMessage()])->setStatusCode($e->getCode());
        }
    }
}
