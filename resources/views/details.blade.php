@extends('layouts.app')
@section('content')
<style>
    .filled-heart {
        color: orange;
    }
    #sizeError,
#colorError {
    color: red !important;
    font-weight: 600;
    font-size: 13px;
position: absolute;
    font-size: 12px;
    margin-top: 2px;
}

.product-single__addtocart .mb-2 {
    position: relative;
    padding-bottom: 18px;
}
.qty-control{
    margin-top: 0;
}

.sold-out-glass {
    position: absolute;
    inset: 0;

    backdrop-filter: blur(6px);
    background: rgba(255, 255, 255, 0.2);

    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 20;
    overflow: hidden; /* QUAN TRỌNG */
}

/* VỆT ĐEN CHÉO */
.sold-out-glass span {
    position: absolute;
    top: 50%;
    left: -30%;

    width: 160%;
    text-align: center;

    background: rgba(235, 8, 8, 0.75);
    color: #fff;

    padding: 12px 0;
    font-size: 20px;
    font-weight: bold;
    letter-spacing: 3px;

    transform: rotate(-25deg);
}
.discount-badge {
    position: absolute;
    top: 10px;
    right: 10px;

    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: #fff;

    padding: 6px 12px;
    font-size: 15px;
    font-weight: 600;

    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);

    z-index: 20;

    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.08); }
    100% { transform: scale(1); }
}
.pc__img-wrapper {
    position: relative;
    overflow: hidden;
}

.pc__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    transition: all 0.7s ease;
}

.main-img {
    transform: translateX(0) scale(1);
    z-index: 1;
}

.hover-img {
    transform: translateX(100%) scale(1);
    z-index: 2;
}

.pc__img-wrapper:hover .main-img {
    transform: translateX(-100%) scale(1.05);
}

.pc__img-wrapper:hover .hover-img {
    transform: translateX(0) scale(1.05);
}
/* Đồng bộ tất cả */
#size, #color,
.qty-control,
.qty-control__number {
    height: 50px;
    width: 165px;
    text-align-last: center;
}

/* Khung quantity */
.qty-control {
    display: flex;
    align-items: center;
    border: 1px solid #ced4da;
}

/* Input */
.qty-control__number {
    border: none;
    text-align: center;
}

/* Nút +/- */
.qty-control__reduce,
.qty-control__increase {
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
/* FIX LỆCH 3 Ô */
.product-single__addtocart {
    display: flex;
    gap: 5px;
    align-items: flex-start; /* QUAN TRỌNG */
}

/* 2 ô select */
.product-single__addtocart .mb-2 {
    flex: 0 0 130px; /* Không giãn, không co, cố định rộng 130px */
    padding-bottom: 0 !important;
}

/* quantity */
.product-single__addtocart .qty-control {
    flex: 0 0 100px; /* Ô số lượng thường nhỏ hơn, để khoảng 100px */
    margin: 0;
}

/* button xuống dòng riêng */
.product-single__addtocart button {
    width: auto;           /* Bỏ width 100% */
    min-width: 200px;      /* Đặt chiều dài tối thiểu bạn muốn */
    padding: 10px 40px;    /* Chỉnh khoảng cách chữ với 2 bên nút */
    margin-top: 10px;
    flex-basis: auto;      /* Thay vì 100% để nó không chiếm cả hàng */
}
.customer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.customer-avatar i {
    font-size: 28px;
    color: #6b7280;
}
.qty-wrapper {
    display: inline-block; /* gom lại 1 khối */
}

.qty-control {
    display: flex;
    align-items: center;
    gap: 6px;
}

#qtyError {
    flex-basis: 100%;   /* ép nó chiếm 1 dòng riêng */
    width: 100%;
    margin-top: 4px;
    font-size: 13px;
    color: #dc3545;
    
}

</style>
<main class="pt-90">
    <div class="mb-md-1 pb-md-3"></div>
    <section class="product-single container">
      <div class="row">
        <div class="col-lg-7">
          <div class="product-single__media" data-media-type="vertical-thumbnail">
    <div class="product-single__image">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                {{-- Ảnh chính --}}
                <div class="swiper-slide product-single__image-item">
                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products/' . $product->image) }}" width="674" height="674" alt="{{ $product->name }}" />
                    <a data-fancybox="gallery" href="{{ asset('uploads/products/' . $product->image) }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_zoom" />
                        </svg>
                    </a>
                </div>
                {{-- Ảnh Gallery --}}
                @foreach(explode(',', $product->images) as $gimg)
                <div class="swiper-slide product-single__image-item">
                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products/' . trim($gimg)) }}" width="674" height="674" alt="" />
                    <a data-fancybox="gallery" href="{{ asset('uploads/products/' . trim($gimg)) }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_zoom" />
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-prev">
               <i class="fa fa-chevron-left"></i>
                 </div>
              <div class="swiper-button-next">
                <i class="fa fa-chevron-right"></i>
                </div>
                  </div>
    </div>
    {{-- Phần Thumbnail (ảnh nhỏ bên dưới/cạnh) --}}
    <div class="product-single__thumbnail">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide product-single__image-item">
                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products/thumbnails/' . $product->image) }}" width="104" height="104" alt="" />
                </div>
                @foreach(explode(',', $product->images) as $gimg)
                <div class="swiper-slide product-single__image-item">
                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products/thumbnails/' . trim($gimg)) }}" width="104" height="104" alt="" />
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
        </div>
        <div class="col-lg-5">
          <div class="d-flex justify-content-between mb-4 pb-md-2">
            <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
              <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Trang Chủ</a>
              <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
              <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Mua Sắm</a>
            </div><!-- /.breadcrumb -->

            {{-- <div
              class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
              <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10" viewBox="0 0 25 25"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_prev_md" />
                </svg><span class="menu-link menu-link_us-s">Trước</span></a>
              <a href="#" class="text-uppercase fw-medium"><span class="menu-link menu-link_us-s">Sau</span><svg
                  width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_next_md" />
                </svg></a>
            </div> --}}
          </div>
     <h1 class="product-single__name">{{ $product->name }}</h1>
    <div class="d-flex align-items-center mt-1">
        <div>
            @php $fullStars = floor($avgRating); @endphp
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $fullStars)
                    <span style="color:orange">★</span>
                @else
                    <span style="color:#f0e50e">★</span>
                @endif
            @endfor
        </div>

    <span class="ms-2 text-muted">
        {{ number_format($avgRating,1) }} ({{ $totalReviews }} đánh giá)
    </span>
</div>
          <div class="product-single__price">
            <span class="current-price">
             @if($product->sale_price)
    <s>{{ number_format($product->regular_price, 0, ',', '.') }} đ </s> 
    {{ number_format($product->sale_price, 0, ',', '.') }} đ 
@else
    {{ number_format($product->regular_price, 0, ',', '.') }} đ
@endif
            </span>
          </div>
          <div class="product-single__short-desc">
            <p>{{$product->short_description}}</p>
          </div>
   {{-- Thay thế form cũ bằng form này --}}
   {{-- @if(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
    <a href="{{ route('cart.index') }}" class="btn btn-warning mb-3">Đi đến trang giỏ hàng</a>
   @else --}}
          @if($product->quantity <= 0)
          <div class="border border-danger rounded-3 p-3 mb-3 bg-light">
                <div class="d-flex align-items-center gap-2 text-danger fw-semibold">
                    <span >Sản phẩm hiện đã hết hàng</span>
                </div>
            </div>
            <button class="btn btn-warning mb-3" disabled>Hết hàng</button>

        @elseif(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
    <a href="{{ route('cart.index') }}" class="btn btn-warning mb-3">Đến giỏ hàng</a>
   @else
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <div class="product-single__addtocart">
                  <div class="mb-2">
                <select name="size" id="size" class="form-control">
                  <option value="">--Chọn Kích Thước--</option>
                  @foreach(json_decode($product->sizes ?? '[]') as $size)
                      <option value="{{ $size }}">{{ $size }}</option>
                  @endforeach
              </select>   
              <small id="sizeError" class="text-danger d-none">
          Vui lòng chọn kích thước
        </small>  
            </div>

            {{-- COLOR --}}
            <div class="mb-2">
                <select name="color" id="color" class="form-control">
                  <option value="">--Chọn Màu--</option>
                  @foreach(json_decode($product->colors ?? '[]') as $color)
                      <option value="{{ $color }}">{{ $color }}</option>
                  @endforeach  
              </select>
              <small id="colorError" class="text-danger d-none">
          Vui lòng chọn màu
        </small>      
            </div>
                <div class="qty-control position-relative">
                    {{-- Ô nhập số lượng --}}
                    <!-- <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center"> -->
                     <input type="number" id="quantity-input" data-stock="{{ $product->quantity }}"name="quantity" value="1" min="1" class="qty-control__number text-center">
                    <div class="qty-control__reduce">-</div>
                    <div class="qty-control__increase">+</div>
                </div>
                <div id="qtyError" style="color:red; font-size:14px; display:none;">
     Số lượng vượt quá tồn kho
</div>
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="name" value="{{ $product->name }}">
                <input type="hidden" name="price" value="{{ $product->sale_price ? $product->sale_price : $product->regular_price }}">

                <button type="submit" class="btn btn-primary btn-addtocart m px-5">THÊM VÀO GIỎ HÀNG</button>
            </div>
        </form>
        @endif
          <div class="product-single__addtolinks">
              @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
              <form method="POST" action="{{ route('wishlist.item.remove',['rowId'=>Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId])}}" id="frm-remove-item">
                @csrf
                @method('DELETE')
            <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist filled-heart" onclick="document.getElementById('frm-remove-item').submit();"><svg width="16" height="16" viewBox="0 0 20 20"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg><span>Xoá Khỏi Danh Sách Yêu Thích</span></a>
              </form>
              @else
                <form method="POST" action="{{ route('wishlist.add') }}" id="wishlist-form">
               @csrf
               <input type="hidden" name="id" value="{{ $product->id }}" />
               <input type="hidden" name="name" value="{{ $product->name }}" />
               <input type="hidden" name="price" value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
               <input type="hidden" name="quantity" value="1" />
              
               <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist " onclick="document.getElementById('wishlist-form').submit();"><svg width="16" height="16" viewBox="0 0 20 20"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg><span>Thêm Vào Danh Sách Yêu Thích</span></a>
                </form>
              @endif


            <share-button class="share-button">
              <button class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center">
                <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_sharing" />
                </svg>
                <span>Chia Sẻ</span>
              </button>
              <details id="Details-share-template__main" class="m-1 xl:m-1.5" hidden="">
                <summary class="btn-solid m-1 xl:m-1.5 pt-3.5 pb-3 px-5">+</summary>
                <div id="Article-share-template__main"
                  class="share-button__fallback flex items-center absolute top-full left-0 w-full px-2 py-4 bg-container shadow-theme border-t z-10">
                  <div class="field grow mr-4">
                    <label class="field__label sr-only" for="url">Link</label>
                    <input type="text" class="field__input w-full" id="url"
                      value="https://uomo-crystal.myshopify.com/blogs/news/go-to-wellness-tips-for-mental-health"
                      placeholder="Link" onclick="this.select();" readonly="">
                  </div>
                  <button class="share-button__copy no-js-hidden">
                    <svg class="icon icon-clipboard inline-block mr-1" width="11" height="13" fill="none"
                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" viewBox="0 0 11 13">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z"
                        fill="currentColor"></path>
                    </svg>
                    <span class="sr-only">Sao Chép link</span>
                  </button>
                </div>
              </details>
            </share-button>
            <script src="js/details-disclosure.html" defer="defer"></script>
            <script src="js/share.html" defer="defer"></script>
          </div>
          <div class="product-single__meta-info">
    <div class="meta-item">
        <label>Mã sp:</label>
        <span>{{ $product->SKU }}</span>
    </div>
    <div class="meta-item">
        <label>Danh muc:</label>
        <span>{{ $product->category->name }}</span>
    </div>
    <div class="meta-item">
              <label>Tags:</label>
              <span>NA</span>
            </div>
</div>
        </div>
      </div>
      <div class="product-single__details-tab">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          {{-- <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
              href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">MÔ TẢ</a>
          </li> --}}
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab"
              href="#tab-additional-info" role="tab" aria-controls="tab-additional-info"
              aria-selected="false">THÔNG TIN SẢN PHẨM</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab" href="#tab-reviews"
              role="tab" aria-controls="tab-reviews" aria-selected="false">ĐÁNH GIÁ</a>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
            aria-labelledby="tab-description-tab">
            <div class="product-single__description">
                {{$product->description}}
           
            </div>
          </div>
        @php
    $sizes = is_array($product->sizes)
        ? $product->sizes
        : json_decode($product->sizes, true) ?? [];

    $colors = is_array($product->colors)
        ? $product->colors
        : json_decode($product->colors, true) ?? [];
@endphp

<div class="tab-pane fade" id="tab-additional-info" role="tabpanel">

  <div class="product-single__addtional-info">

    {{-- CÂN NẶNG --}}
    @if($product->weight)
    <div class="item d-flex justify-content-between align-items-center py-2 border-bottom">
      <span class="text-muted">Cân nặng</span>
      <span class="fw-semibold">{{ $product->weight }} kg</span>
    </div>
    @endif

    {{-- KÍCH THƯỚC --}}
    @if($product->dimensions)
    <div class="item d-flex justify-content-between align-items-center py-2 border-bottom">
      <span class="text-muted">Kích thước</span>
      <span class="fw-semibold">{{ $product->dimensions }}</span>
    </div>
    @endif

    {{-- SIZE --}}
@if(!empty($sizes))
<div class="item py-2 border-bottom">
  <div class="text-muted mb-1 fw-medium">Kích thước</div>

  <div class="d-flex flex-wrap gap-2">
    @foreach($sizes as $size)
      <span class="px-3 py-1 border rounded-2 bg-white small shadow-sm">
        {{ trim($size) }}
      </span>
    @endforeach
  </div>
</div>
@endif

{{-- COLOR --}}
@if(!empty($colors))
<div class="item py-2 border-bottom">
  <div class="text-muted mb-1 fw-medium">Màu sắc</div>

  <div class="d-flex flex-wrap gap-2">
    @foreach($colors as $color)
      <span class="px-3 py-1 rounded-2 small text-white shadow-sm"
            style="background: #2d2d2d;">
        {{ trim($color) }}
      </span>
    @endforeach
  </div>
</div>
@endif
    {{-- MÔ TẢ --}}
    @if($product->description)
    <div class="item py-3">
      <span class="text-muted d-block mb-1">Mô tả sản phẩm</span>
      <span class="text-secondary lh-lg">
        {{ $product->description }}
      </span>
    </div>
    @endif

  </div>

</div>
          <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
            <h2 class="product-single__reviews-title">TOÀN BỘ ĐÁNH GIÁ</h2>
            <div class="product-single__reviews-list">
              @if($reviews->isEmpty())
                    <p>Chưa có đánh giá nào </p>
                @else
                    @foreach($reviews as $review)
                    <div class="product-single__reviews-item">

                        <div class="customer-avatar">
    <i class="fa fa-user-circle"></i>
</div>

                        <div class="customer-review">
                            <div class="customer-name">
                                <h6>{{ $review->user->name ?? 'Ẩn danh' }}</h6>

                                <div class="reviews-group d-flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <span style="color:orange">★</span>
                                        @else
                                            <span style="color:#ccc">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            <div class="review-date">
                                {{ $review->created_at->format('d/m/Y') }}
                            </div>

                            <div class="review-text">
                                <p>{{ $review->comment }}</p>
                            </div>
                        </div>

                    </div>
                    @endforeach
                @endif
              

            <div class="product-single__review-form">
              <form method="POST" action="{{ route('product.review') }}">
    @csrf

    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <h5>Hãy là người đầu tiên đánh giá</h5>
    <p>Email của bạn sẽ không được hiển thị công khai. Các trường bắt buộc được đánh dấu *</p>

    {{-- ⭐ RATING --}}
    <label>Đánh giá của bạn *</label>
    <span class="star-rating">
        @for($i = 1; $i <= 5; $i++)
        <svg class="star-rating__star-icon" data-value="{{ $i }}" width="20" height="20" fill="#ccc"
            viewBox="0 0 12 12">
            <path d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z"/>
        </svg>
        @endfor
    </span>

    <input type="hidden" name="rating" id="form-input-rating" required>

    {{-- COMMENT --}}
    <div class="mb-4 mt-2">
        <textarea name="comment" class="form-control form-control_gray"
            placeholder="Điền nội dung..." cols="30" rows="5" required></textarea>
    </div>

    {{-- CHECK LOGIN --}}
    @guest
        <p class="text-danger"> Vui lòng đăng nhập để đánh giá</p>
    @endguest

    <div class="form-action">
        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
    </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="products-carousel container">
      <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">SẢN PHẨM<strong> ĐỀ XUẤT</strong></h2>

      <div id="related_products" class="position-relative">
        <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
              "el": "#related_products .products-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": "#related_products .products-carousel__next",
              "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 14
              },
              "768": {
                "slidesPerView": 3,
                "slidesPerGroup": 3,
                "spaceBetween": 24
              },
              "992": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              }
            }
          }'>
            <div class="swiper-wrapper">
        @foreach($rproducts as $rproduct)
        <div class="swiper-slide product-card">
            <div class="pc__img-wrapper">
                <a href="{{ route('shop.product.details', ['product_slug' => $rproduct->slug]) }}">
                    <img loading="lazy" src="{{ asset('uploads/products/' . $rproduct->image) }}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img">
                    @php $gimages = explode(',', $rproduct->images); @endphp
                    @if(count($gimages) > 0 && $gimages[0] != '')
                        <img loading="lazy" src="{{ asset('uploads/products/' . trim($gimages[0])) }}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img pc__img-second">
                    @endif
                </a>
                              @if($rproduct->sale_price && $rproduct->regular_price > 0)
                  @php
                      $discount = round(100 - ($rproduct->sale_price / $rproduct->regular_price * 100));
                  @endphp
                  <span class="discount-badge">-{{ $discount }}%</span>
              @endif
                
                    @if($rproduct->quantity <= 0)
                        <div class="sold-out-glass">
                            <span>HẾT HÀNG</span>
                        </div>
                    @endif
              <button class="pc__atc btn ...">Thêm Vào Giỏ Hàng</button>
            </div>
            <div class="pc__info position-relative">
                <p class="pc__category">{{ $rproduct->category->name }}</p>
                <h6 class="pc__title">
                    <a href="{{ route('shop.product.details', ['product_slug' => $rproduct->slug]) }}">{{ $rproduct->name }}</a>
                </h6>
                <div class="product-card__price d-flex">
                    <span class="money price">{{ number_format((int)$rproduct->regular_price, 0, ',', '.') }}  đ</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
            </div><!-- /.swiper-container js-swiper-slider -->

            <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
              <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_prev_md" />
              </svg>
            </div><!-- /.products-carousel__prev -->
            <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
              <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_next_md" />
              </svg>
            </div><!-- /.products-carousel__next -->

            <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
            <!-- /.products-pagination -->
          </div><!-- /.position-relative -->

        </section><!-- /.products-carousel container -->
      </main>
      <script>
        document.querySelector("form[action='{{ route('cart.add') }}']").addEventListener("submit", function(e) {

    let size = document.getElementById("size").value;
    let color = document.getElementById("color").value;

    let quantityInput = document.getElementById("quantity-input");
    let quantity = parseInt(quantityInput.value);
    let maxStock = parseInt(quantityInput.dataset.stock);

    let qtyError = document.getElementById("qtyError");

    let hasError = false;

    // reset
    document.getElementById("sizeError").classList.add("d-none");
    document.getElementById("colorError").classList.add("d-none");
    if (qtyError) qtyError.style.display = "none";

    // check size
    if (size === "") {
        document.getElementById("sizeError").classList.remove("d-none");
        hasError = true;
    }

    // check color
    if (color === "") {
        document.getElementById("colorError").classList.remove("d-none");
        hasError = true;
    }

    // check quantity
    if (quantity > maxStock) {
        if (qtyError) {
            qtyError.style.display = "block";
        } else {
            alert("Chỉ còn " + maxStock + " sản phẩm!");
        }
        hasError = true;
    }

    if (quantity < 1) {
        alert("Số lượng phải >= 1");
        hasError = true;
    }

    if (hasError) {
        e.preventDefault();
    }
});
//     document.querySelector("form[action='{{ route('cart.add') }}']").addEventListener("submit", function(e) {
//         let size = document.getElementById("size").value;
//         let color = document.getElementById("color").value;
//         let quantity = document.getElementById("quantity-input").value;
//         let maxStock = document.getElementById("quantity-input").dataset.stock;
//         let qtyError = document.getElementById("qtyError");

//         let hasError = false;

//         // reset
//         document.getElementById("sizeError").classList.add("d-none");
//         document.getElementById("colorError").classList.add("d-none");

//         // check size
//         if (size === "") {
//             document.getElementById("sizeError").classList.remove("d-none");
//             hasError = true;
//         }

//         // check color
//         if (color === "") {
//             document.getElementById("colorError").classList.remove("d-none");
//             hasError = true;
//         }
//          if (parseInt(quantity) > parseInt(maxStock)) {
//         if (qtyError) {
//             qtyError.classList.remove("d-none");
//         } else {
//             alert("Chỉ còn " + maxStock + " sản phẩm trong kho!");
//         }
//         hasError = true;
//     }

//     if (parseInt(quantity) < 1) {
//         alert("Số lượng phải >= 1");
//         hasError = true;
//     }

//         if (hasError) {
//             e.preventDefault(); // CHẶN SUBMIT
//         }
// });
</script>
@endsection