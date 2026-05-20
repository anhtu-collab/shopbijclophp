<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // Lấy lịch sử chat cũ khi vừa mở box chat
    public function fetchMessages(Request $request)
    {
        $userId = Auth::id();
        $token = $request->cookie('chat_token');

        if ($userId) {
            $messages = ChatMessage::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        } elseif ($token) {
            $messages = ChatMessage::where('token', $token)->orderBy('created_at', 'asc')->get();
        } else {
            $messages = collect();
        }

        return response()->json($messages);
    }

    // Gửi tin nhắn và nhận phản hồi từ Gemini
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:20000',
        ]);

        $userMessage = $request->message;
        $userId = Auth::id();
        $token = $request->cookie('chat_token');
        $cookieToSet = null;

        // Xử lý cookie token cho khách chưa đăng nhập
        if (!$userId) {
            if (!$token) {
                $token = 'guest_' . Str::random(32);
                // Set cookie lưu trong 180 ngày (nửa năm như video giải thích)
                $cookieToSet = cookie('chat_token', $token, 60 * 24 * 180);
            }
        }

        // 1. Lưu tin nhắn của User vào DB
        $userChat = ChatMessage::create([
            'user_id' => $userId,
            'token' => $userId ? null : $token,
            'sender' => 'user',
            'message' => $userMessage,
        ]);

        // 2. Chuẩn bị dữ liệu Sản Phẩm để "huấn luyện" (Context Prompt)
        $products = Product::where('stock', '>', 0)->get(['name', 'price', 'unit', 'description']);
        // Lấy một số sản phẩm nổi bật hoặc ngẫu nhiên để làm ngữ cảnh,
        // tránh gửi quá nhiều dữ liệu làm vượt quá giới hạn token của Gemini.
        // Đồng thời, sử dụng accessor getTotalStockAttribute để kiểm tra tồn kho.
        $products = Product::where('featured', true) // Lấy sản phẩm nổi bật
                           ->orWhere(function($query) {
                               $query->inRandomOrder()->limit(10); // Hoặc 10 sản phẩm ngẫu nhiên
                           })
                           ->get(['name', 'price', 'sale_price', 'description'])
                           ->filter(fn($product) => $product->total_stock > 0); // Lọc sản phẩm còn hàng
        $productArray = $products->map(function ($prod) {
            return "- Tên: {$prod->name}, Giá: " . number_format($prod->price) . "đ/{$prod->unit}. Mô tả: {$prod->description}";
            $price = $prod->sale_price ?? $prod->price; // Ưu tiên giá khuyến mãi
            return "- Tên: {$prod->name}, Giá: " . number_format($price) . "đ. Mô tả: {$prod->description}";
        })->toArray();
        $productContext = implode("\n", $productArray);

        $systemInstruction = "Bạn là trợ lý bán hàng chuyên nghiệp cho website bán rau củ quả và thực phẩm sạch. "
            . "Dưới đây là danh sách sản phẩm hiện có trong kho hàng:\n" . $productContext . "\n"
            . "Hãy trả lời khách hàng thật ngắn gọn, trung thực, lịch sự và CHỈ sử dụng thông tin từ danh sách sản phẩm được cung cấp bên trên. Nếu sản phẩm khách cần không có trong danh sách, hãy khéo léo từ chối.";

        // 3. Lấy 6 tin nhắn gần nhất làm lịch sử hội thoại (Chat History)
        $historyLogs = ChatMessage::where($userId ? 'user_id' : 'token', $userId ? $userId : $token)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        $contents = [];
        foreach ($historyLogs as $log) {
            $contents[] = [
                'role' => $log->sender == 'user' ? 'user' : 'model',
                'parts' => [['text' => $log->message]],
            ];
        }

        // Thêm câu hỏi hiện tại vào chuỗi hội thoại gửi lên Gemini
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        // 4. Gọi API Gemini
        $apiKey = env('GOOGLE_GEMINI_API_KEY');
        $botReply = "Xin lỗi, hệ thống AI đang bận. Vui lòng thử lại sau!";

        if ($apiKey) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->post($url, [
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ],
                    'contents' => $contents
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Tôi chưa hiểu câu hỏi của bạn.";
                }
            } catch (\Exception $e) {
                Log::error("Gemini API Error: " . $e->getMessage());
                $botReply = "Xin lỗi, AI không thể xử lý lúc này.";
            }
        }

        // 5. Lưu câu trả lời của Bot vào DB
        $botChat = ChatMessage::create([
            'user_id' => $userId,
            'token' => $userId ? null : $token,
            'sender' => 'bot',
            'message' => $botReply
        ]);

        // Trả kết quả về cho giao diện (AJAX)
        $res = response()->json([
            'user_message' => $userChat,
            'bot_message' => $botChat
        ]);

        if ($cookieToSet) {
            return $res->cookie($cookieToSet);
        }
        return $res;
    }
}

