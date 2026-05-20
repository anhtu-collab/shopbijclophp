<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    // Khai báo tên bảng trong database nếu bạn tạo bảng là chat_messages
    protected $table = 'chat_messages';

    // Cho phép Laravel lưu nhanh các trường này vào DB bằng hàm create()
    protected $fillable = [
        'user_id',
        'token',
        'sender',
        'message',
    ];

    // Tạo mối quan hệ với bảng Users (Nếu cần dùng sau này)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
