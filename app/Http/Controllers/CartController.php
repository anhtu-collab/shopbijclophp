<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Surfsidemedia\Shoppingcart\Facades\Cart; 

class CartController extends Controller
{
    public function index() {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request) {
        Cart::instance('cart')->add(
            $request->id, 
            $request->name, 
            $request->quantity, 
            $request->price
        )->associate('App\Models\Product'); // Liên kết với Model Product để lấy ảnh
        
        return redirect()->back();
    }
    // Hàm tăng số lượng
public function increase_cart_quantity($rowId) {
    // Lấy thông tin sản phẩm từ giỏ hàng bằng rowId [00:01:29]
    $product = Cart::instance('cart')->get($rowId);
    $qty = $product->qty + 1; // Tăng lên 1
    
    // Cập nhật lại giỏ hàng [00:01:54]
    Cart::instance('cart')->update($rowId, $qty);
    return redirect()->back();
}

// Hàm giảm số lượng
public function decrease_cart_quantity($rowId) {
    $product = Cart::instance('cart')->get($rowId);
    $qty = $product->qty - 1; // Giảm đi 1
    
    Cart::instance('cart')->update($rowId, $qty);
    return redirect()->back();
}
// Hàm xóa 1 sản phẩm cụ thể
public function remove_item($rowId) {
    // Sử dụng phương thức remove() với rowId [00:01:11]
    Cart::instance('cart')->remove($rowId);
    return redirect()->back();
}

// Hàm xóa sạch giỏ hàng
public function empty_cart() {
    // Sử dụng phương thức destroy() để xóa toàn bộ [00:07:26]
    Cart::instance('cart')->destroy();
    return redirect()->back();
}
}
