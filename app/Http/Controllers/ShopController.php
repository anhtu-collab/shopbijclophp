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
    
        $products = Product::with(['variants.size', 'variants.color'])
            ->withCount([
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

    $rproducts = Product::where('category_id', $product->category_id)
    ->where('id', '!=', $product->id)
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
    $totalStock = ProductVariant::where('product_id', $product->id)
    ->sum('quantity');

    return view('details', compact('product','rproducts','reviews','avgRating','totalReviews','totalStock'));
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

    if ($product->is_out_of_stock) {
        return back()->with('error', 'Sản phẩm này hiện đã hết hàng!');
    }

    $stock = ProductVariant::where('product_id', $product->id)
        ->sum('quantity');

    if ($stock <= 0) {
        return back()->with('error', 'Sản phẩm đã hết hàng!');
    }

if ($request->quantity > $stock) {
    return back()->with('error', 'Số lượng vượt tồn kho!');
}

$variantQuery = ProductVariant::where('product_id', $product->id);

if (is_numeric($request->size)) {
    $variantQuery->where('size_id', $request->size);
} elseif ($request->size) {
    $variantQuery->whereHas('size', function ($q) use ($request) {
        $q->where('name', $request->size);
    });
}

if (is_numeric($request->color)) {
    $variantQuery->where('color_id', $request->color);
} elseif ($request->color) {
    $variantQuery->whereHas('color', function ($q) use ($request) {
        $q->where('name', $request->color);
    });
}

$variant = $variantQuery->first();

$sizeName = $variant ? $variant->size->name : $request->size;
$colorName = $variant ? $variant->color->name : $request->color;
    \Surfsidemedia\Shoppingcart\Facades\Cart::instance('cart')->add(
        $product->id,
        $product->name,
        $request->quantity,
        $product->sale_price ?? $product->regular_price,
        [
            'size_id' => $variant ? $variant->size_id : null,
            'color_id' => $variant ? $variant->color_id : null,
            'size' => $sizeName,
            'color' => $colorName,
        ]
    )->associate(Product::class);

    return redirect()->route('cart.index');
}

    
}
