<?php

namespace App\Services;

use App\Models\Chat;

class ChatService
{
    public function get(int $from_user, int $to_user)
    {
        try {
            $chat = Chat::where(function ($query) use ($from_user, $to_user) {
                $query->where('from_user', $from_user)
                    ->orWhere('to_user', $to_user);
            })
                ->orWhere(function ($query) use ($from_user, $to_user) {
                    $query->where('to_user', $from_user)
                        ->orWhere('from_user', $to_user);
                })
                ->orderBy('created_at')->get();

            return ['data' => $chat, 'code' => 200];
        }
        catch (\Exception $exception)
        {
            return ['data' => $exception->getMessage(), 'code' => $exception->getCode()];
        }

    }

    public function sendMessage(int $from_user, int $to_user, string $message)
    {
        try {
            Chat::create([
                'from_user' => $from_user,
                'to_user' => $to_user,
                'message' => $message
                ]);
            return ['message' => 'success', 'code' => 200];
        }
        catch (\Exception $exception)
        {
            return ['message' => $exception->getMessage(), 'code' => 500];
        }
    }
}
