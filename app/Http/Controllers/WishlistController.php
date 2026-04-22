<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index()
{
    // Lấy toàn bộ sản phẩm trong instance 'wishlist'
    $items = Cart::instance('wishlist')->content();
    
    // Trả về view 'wishlist' và truyền biến $items sang
    return view('wishlist', compact('items'));
}
   public function add_to_wishlist(Request $request)
    {
        Cart::instance('wishlist')->add($request->id, $request->name,$request->quantity,$request->price)->associate('App\\Models\\Product');
        return redirect()->back();
    }
    // WishlistController.php

public function remove_item($rowId) {
    // Sử dụng thư viện Cart/Wishlist để xóa theo rowId
    Cart::instance('wishlist')->remove($rowId);
    return back();
}

public function empty_wishlist() {
    // Xóa toàn bộ nội dung trong instance wishlist
    Cart::instance('wishlist')->destroy();
    return back();
}
// WishlistController.php

public function move_to_cart($rowId) {
    // 1. Lấy item từ thực thể (instance) wishlist theo rowId
    $item = Cart::instance('wishlist')->get($rowId);

    // 2. Xóa item đó khỏi wishlist
    Cart::instance('wishlist')->remove($rowId);

    // 3. Thêm item đó vào thực thể cart
    // Lưu ý: Cần truyền đúng ID, tên, số lượng (thường là 1) và giá
    Cart::instance('cart')->add($item->id, $item->name, 1, $item->price)
        ->associate('App\Models\Product'); // Liên kết với Model Product

    return redirect()->back()->with('status', 'Sản phẩm đã được chuyển sang giỏ hàng!');
}
}
