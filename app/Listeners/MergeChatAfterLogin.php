<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Request;

class MergeChatAfterLogin
{
    public function handle(Login $event): void
    {
        // Lấy token từ cookie của khách trước khi đăng nhập
        $token = Request::cookie('chat_token');
        $user = $event->user;

        if ($token) {
            // Cập nhật tất cả tin nhắn cũ có token này sang User ID của người vừa đăng nhập
            ChatMessage::where('token', $token)->update([
                'user_id' => $user->id,
                'token' => null // Xóa token đi vì đã thuộc về User cụ thể
            ]);
        }
    }
}
