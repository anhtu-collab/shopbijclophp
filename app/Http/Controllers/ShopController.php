<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size') ? $request->query('size') : 12;
        $o_clomn ="";
        $o_oder ="";
        $order = $request->query('order') ? $request->query('order') : -1;
        $f_brands= $request->query('brands');
        $f_categories = $request->query('categories', '');
        $category = $request->query('category');
        $min_price = $request->query('min_price') ? $request->query('min_price') : 100; 
        $max_price = $request->query('max_price') ? $request->query('max_price') : 10000000;
        switch($order)
{
    case 1:
        $o_column = 'created_at';
        $o_order = 'DESC';
        break;

    case 2:
        $o_column = 'created_at';
        $o_order = 'ASC';
        break;

    case 3:
        $o_column = 'sale_price';
        $o_order = 'ASC';
        break;

    case 4:
        $o_column = 'sale_price';
        $o_order = 'DESC';
        break;

    default:
        $o_column = 'id';
        $o_order = 'DESC';
    }
         $brands = Brand::orderBy('name', 'ASC')->get();
         $categories = Category::orderBy('name', 'ASC')->get();
        //   $products = Product::where(function($query) use($f_brands){
        $products = Product::withCount([
        'reviews as reviews_count' => function ($q) {
            $q->where('status', 'approved');
        }
    ])
    ->withAvg([
        'reviews as rating' => function ($q) {
            $q->where('status', 'approved');
        }
    ], 'rating')
    ->where(function($query) use($f_brands){
            $query->whereIn('brand_id', explode(',', $f_brands))->orWhereRaw("'" . $f_brands . "' = ''");
          })
         ->where(function ($query) use ($f_categories, $category) {

    if (!empty($category)) {
        $query->where('category_id', $category);
    } 
    elseif (!empty($f_categories)) {
        $query->whereIn('category_id', array_filter(explode(',', $f_categories)));
    }

})
          
         ->where(function($query) use ($min_price, $max_price) {
            $query->whereBetween('regular_price', [$min_price, $max_price])
                ->orWhereBetween('sale_price', [$min_price, $max_price]);
})
                    ->orderBy($o_column, $o_order)->paginate($size);
        return view('shop', compact('products','size','order','brands','f_brands','categories','f_categories','min_price','max_price'));
    }
    public function product_details($product_slug) 
{
    $product = Product::where('slug', $product_slug)->firstOrFail();

    // $rproducts = Product::where('slug', '!=', $product_slug)
    //     ->inRandomOrder()
    //     ->take(8)
    //     ->get();
    $rproducts = Product::where('category_id', $product->category_id)
    ->where('id', '!=', $product->id) // bỏ chính nó
    ->latest()
    ->take(8)
    ->get();

     $reviews = Review::with('user')
    ->where('product_id', $product->id)
    ->where('status', 'approved')
    ->latest()
    ->get();

    $avgRating = $reviews->avg('rating');
    $totalReviews = $reviews->count();

    return view('details', compact('product','rproducts','reviews','avgRating','totalReviews'));
}
public function store_review(Request $request)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
        'product_id' => 'required|exists:products,id'
    ]);

    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
        'status' => 'pending'
    ]);

    return back()->with('success', 'Gửi đánh giá thành công!');
}
public function quickView(Product $product)
{
    return view('quickview', compact('product'));
}
public function buyNow(Request $request)
{
    $product = Product::find($request->id);

    if (!$product) {
        return back()->with('error', 'Sản phẩm không tồn tại!');
    }

    if ($product->quantity <= 0) {
        return back()->with('error', 'Sản phẩm đã hết hàng!');
    }

    if ($request->quantity > $product->quantity) {
        return back()->with('error', 'Số lượng vượt tồn kho!');
    }

    // xoá cart cũ (buy now chỉ 1 sản phẩm)
    \Surfsidemedia\Shoppingcart\Facades\Cart::instance('cart')->destroy();

    \Surfsidemedia\Shoppingcart\Facades\Cart::instance('cart')->add(
        $product->id,
        $product->name,
        $request->quantity,
        $product->sale_price ?? $product->regular_price,
        [
            'size' => $request->size,
            'color' => $request->color,
        ]
    )->associate(Product::class);

    return redirect()->route('cart.index');
}

    
}
