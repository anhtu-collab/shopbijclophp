<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WishlistController;
use Faker\Provider\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ManaController;
use App\Http\Controllers\Client\ChatController;
Auth::routes();

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.empty');

Route::post('/cart/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');

Route::post('/buy-now', [ShopController::class, 'buyNow'])->name('buy.now');


Route::post('/wishlist/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/item/remove/{rowId}', [WishlistController::class, 'remove_item'])->name('wishlist.item.remove');
Route::delete('/wishlist/clear', [WishlistController::class, 'empty_wishlist'])->name('wishlist.items.clear');
Route::post('/wishlist/move-to-cart/{rowId}', [WishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');

Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place.an.order');
Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.order.confirmation'); 

Route::get('/contact-us', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact/store', [HomeController::class, 'contact_store'])->name('home.contact.store');

Route::get('/search', [HomeController::class, 'search'])->name('home.search');

Route::middleware(['auth'])->group(function() {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
      Route::get('/account-address', [UserController::class, 'address'])->name('user.address');
      Route::get('/account-address/add', [UserController::class, 'add_address'])->name('user.address.add');
      Route::get('/account-address/edit/{id}', [UserController::class, 'edit_address'])->name('user.address.edit');
    Route::post('/account-address/update/{id}', [UserController::class, 'update_address'])->name('user.address.update');
    Route::post('/address/store', [UserController::class, 'store_address'])->name('user.address.store');

      Route::get('/account-details', [UserController::class, 'details'])->name('user.details');
       Route::post('/account-update', [UserController::class, 'update'])->name('user.update');

    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account/order/{order_id}/details', [UserController::class, 'order_details'])->name('user.order.details');
    Route::put('/account/order/cancel-order', [UserController::class, 'order_cancel'])->name('user.order.cancel');
});

    Route::middleware(['auth','admin'])->group(function(){
    Route::get('/admin',[AdminController::class ,'index'])->name('admin.index');
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add',[AdminController::class,'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store',[AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}',[AdminController::class,'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update',[AdminController::class,'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete',[AdminController::class,'brand_delete'])->name('admin.brand.delete');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/category/add',[AdminController::class,'category_add'])->name('admin.category.add');
    Route::post('/admin/category/store',[AdminController::class,'category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit',[AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update',[AdminController::class,'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete',[AdminController::class,'category_delete'])->name('admin.category.delete');
     
   Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
   Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
   Route::post('/admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
   Route::get('/admin/product/{id}/edit', [AdminController::class, 'product_edit'])->name('admin.product.edit');
   Route::put('/admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
   Route::delete('/admin/product/{id}/delete',[AdminController::class,'product_delete'])->name('admin.product.delete');

   Route::post('/admin/variant/store', [AdminController::class, 'variant_store'])->name('admin.variant.store');
   
   Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
   Route::get('/admin/coupon/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
   Route::get('/admin/coupon/details/{id}', [AdminController::class, 'coupon_details'])->name('admin.coupon.details');
   Route::post('/admin/coupon/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
   Route::get('/admin/coupon/{id}/edit', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
   Route::put('/admin/coupon/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
   Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

   Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
   Route::get('/admin/order/{order_id}/details', [AdminController::class, 'order_details'])->name('admin.order.details');
   Route::put('/admin/order/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');
   Route::get('/admin/order/{order_id}/invoice', [AdminController::class, 'order_invoice'])->name('admin.order.invoice');
   Route::get('/admin/order/tracking', [AdminController::class, 'order_tracking'])->name('admin.order.tracking');


    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/add', [AdminController::class, 'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');

    Route::get('/admin/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::delete('/admin/contact/{id}/delete', [AdminController::class, 'contact_delete'])->name('admin.contact.delete');

    Route::get('/admin/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::put('/admin/review/{id}/status', [AdminController::class, 'review_update_status'])->name('admin.review.status');
    Route::put('/admin/reviews/approve-all', [AdminController::class, 'review_approve_all'])->name('admin.reviews.approve_all');
    Route::delete('/admin/review/{id}/delete', [AdminController::class, 'review_delete'])->name('admin.review.delete');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
     Route::get('/admin/stock', [AdminController::class, 'stock'])->name('admin.stock');

    Route::get('/admin/trade', [AdminController::class, 'trade'])->name('admin.trade');
    Route::get('/admin/product_stock', [AdminController::class, 'product_stock'])->name('admin.product_stock');
        });
    

        
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/user/{id}/detail', [AdminController::class, 'user_detail'])->name('admin.users.detail');
    Route::get('/admin/users/add', [AdminController::class, 'users_add'])->name('admin.users.add');
    Route::post('/admin/users/store', [AdminController::class, 'users_store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'users_edit'])->name('admin.users.edit');
    Route::put('/admin/users/update', [AdminController::class, 'users_update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}/delete', [AdminController::class, 'users_delete'])->name('admin.users.delete'); 

    Route::get('/blogs', [HomeController::class, 'blogs'])->name('views.blogs');
    Route::get('/blogs/{slug}', [HomeController::class, 'blog_detail'])->name('views.blog.detail');
        
    Route::prefix('admin')->group(function () {
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::get('/blog/add', [AdminController::class, 'blog_add'])->name('admin.blog.add');
    Route::post('/blog/store', [AdminController::class, 'blog_store'])->name('admin.blog.store');
    Route::get('/blog/edit/{id}', [AdminController::class, 'blog_edit'])->name('admin.blog.edit');
    Route::put('/blog/update', [AdminController::class, 'blog_update'])->name('admin.blog.update');
    Route::delete('/blog/delete/{id}', [AdminController::class, 'blog_delete'])->name('admin.blog.delete');

    Route::get('/admin/transaction', [AdminController::class, 'transaction'])->name('admin.transaction');


});


    Route::get('/payment/vnpay', [PaymentController::class, 'vnpay_payment'])->name('payment.vnpay');
     Route::get('/payment/vnpay/return', [PaymentController::class, 'vnpay_return'])->name('payment.vnpay.return');
    

     Route::middleware(['auth','mana'])->group(function(){
     Route::get('/mana',[ManaController::class ,'index'])->name('mana.index');
     
    
    
     });
     Route::post('/product/review', [ShopController::class, 'store_review'])->name('product.review')->middleware('auth');
     Route::get('/product/quickview/{id}', [ShopController::class, 'quickView'])->name('shop.product.quickview');
      

    Route::prefix('chat')->group(function () {
    Route::get('/messages', [ChatController::class, 'fetchMessages']);
    Route::post('/send', [ChatController::class, 'sendMessage']);
});

    
    