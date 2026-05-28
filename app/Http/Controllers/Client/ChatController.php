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

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:20000',
        ]);

        $userMessage = $request->message;
        $userId = Auth::id();
        $token = $request->cookie('chat_token');
        $cookieToSet = null;

        if (!$userId) {
            if (!$token) {
                $token = 'guest_' . Str::random(32);
                $cookieToSet = cookie('chat_token', $token, 60 * 24 * 180);
            }
        }

        $userChat = ChatMessage::create([
            'user_id' => $userId,
            'token' => $userId ? null : $token,
            'sender' => 'user',
            'message' => $userMessage,
        ]);

        $products = $this->searchProducts($userMessage);

        if ($products->count() == 0) {
            $products = Product::with('variants')
                ->whereHas('variants', function ($q) {
                    $q->where('quantity', '>', 0);
                })
                ->inRandomOrder()
                ->limit(3)
                ->get(['name', 'regular_price', 'sale_price', 'description']);
        }

        Log::info("Found " . $products->count() . " products with stock");
        $productArray = $products->map(function ($prod) {
            $price = $prod->sale_price ?? $prod->regular_price;
            return "- Tên: {$prod->name}, Giá: " . number_format($price) . "đ. Mô tả: {$prod->description}";
        })->toArray();
        $productContext = implode("\n", $productArray);

        $systemInstruction = 
            "Bạn là trợ lý bán hàng cho website thời trang BrijClo, chuyên bán quần áo hiện đại, trẻ trung và hợp xu hướng.\n\n"
            . "Danh sách sản phẩm hiện có:\n"
            . $productContext . "\n\n"
            . "Quy tắc trả lời:\n"
            . "- Chỉ được sử dụng thông tin từ danh sách sản phẩm trên\n"
            . "- Trả lời ngắn gọn, thân thiện, giống nhân viên tư vấn thời trang\n"
            . "- Luôn xưng hô lịch sự (bạn - shop)\n"
            . "- Nếu khách hỏi chung chung (ví dụ: 'có đồ đẹp không') → gợi ý 2-3 sản phẩm nổi bật\n"
            . "- Nếu khách hỏi theo nhu cầu (đi chơi, đi học, đi tiệc...) → tư vấn outfit phù hợp\n"
            . "- Nếu không có sản phẩm → từ chối lịch sự và gợi ý sản phẩm gần giống\n"
            . "- Ưu tiên giới thiệu sản phẩm đang giảm giá hoặc hot trend\n"
            . "- Không được bịa thông tin ngoài danh sách\n";

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

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        $apiKey = env('GOOGLE_GEMINI_API_KEY');
        Log::info("API Key check: " . ($apiKey ? "Found" : "Not found"));
        $botReply = "Xin lỗi, hệ thống AI đang bận. Vui lòng thử lại sau!";

        if ($apiKey) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";
                Log::info("Calling Gemini API URL: " . $url);

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])->post($url, [
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ],
                    'contents' => $contents
                ]);

                Log::info("Gemini API Status: " . $response->status());
                Log::info("Gemini API Response: " . $response->body());

                if ($response->successful()) {
                    $data = $response->json();
                    $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Tôi chưa hiểu câu hỏi của bạn.";
                } else {
                    Log::error("Gemini API failed: " . $response->status() . " - " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Gemini API Error: " . $e->getMessage());
                $botReply = "Xin lỗi, AI không thể xử lý lúc này.";
            }
        } else {
            Log::error("API KEY is missing or empty");
        }

        $botChat = ChatMessage::create([
            'user_id' => $userId,
            'token' => $userId ? null : $token,
            'sender' => 'bot',
            'message' => $botReply
        ]);

        $res = response()->json([
            'user_message' => $userChat,
            'bot_message' => $botChat
        ]);

        if ($cookieToSet) {
            return $res->cookie($cookieToSet);
        }
        return $res;
    }

   function searchProducts($keyword)
{
    return Product::with('variants')
        ->where('name', 'like', "%$keyword%")
        ->orWhere('description', 'like', "%$keyword%")
        ->limit(5)
        ->get();
}
}

