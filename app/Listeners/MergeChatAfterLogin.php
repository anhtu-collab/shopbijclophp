<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Request;

class MergeChatAfterLogin
{
    public function handle(Login $event): void
    {
        $token = Request::cookie('chat_token');
        $user = $event->user;

        if ($token) {
            ChatMessage::where('token', $token)->update([
                'user_id' => $user->id,
                'token' => null
            ]);
        }
    }
}
