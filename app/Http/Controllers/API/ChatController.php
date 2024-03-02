<?php

namespace App\Http\Controllers\API;

use App\Events\SendMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index(User $user)
    {
        $result = $this->chatService->get(config('app.user'), $user->id);
        return response(['data' => $result['data']])->setStatusCode($result['code']);
    }

    public function sendMessage(Request $request, User $user)
    {
        $data = $request->validate(['message' => 'required']);
        $result = $this->chatService->sendMessage(config('app.user'), $user->id, $data['message']);
        broadcast(new SendMessageEvent($user->id, $data['message']));
        return response(['message' => $result['message']])->setStatusCode($result['code']);
    }
}
