@extends('layouts.app')
@section('content')
<style>
.pc__btn-wl {
    position: static !important;
    top: auto !important;
    right: auto !important;

    opacity: 1;
    visibility: visible;
    transition: 0.3s;

    display: inline-flex;
    align-items: center;
    cursor: pointer;
}
.product-card:hover .pc__btn-wl {
    opacity: 1;
}

/* tim màu cam */
.filled-heart {
    color: orange !important;
}
.wishlist-box {
    display: flex;
    gap: 5px;
    align-items: center;
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

@media (max-width: 768px) {
    .pc__img-wrapper {
        aspect-ratio: 1/1;
        max-height: 300px;
    }

    .product-card {
        margin-bottom: 20px;
    }

    .discount-badge {
        font-size: 12px;
        padding: 4px 8px;
    }

    .product-price {
        font-size: 18px !important;
    }

    .product-old-price {
        font-size: 14px !important;
    }

    .btn-add-cart {
        padding: 8px 16px;
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .pc__img-wrapper {
        aspect-ratio: 1/1;
        height: 250px;
    }

    .discount-badge {
        font-size: 10px;
        padding: 3px 6px;
    }
}
.pc__img-wrapper {
    position: relative;
    overflow: hidden;
    width: 100%;
    aspect-ratio: 330 / 400;
}


.pc__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    image-rendering: auto;
    transition: transform 0.7s ease;
}

.main-img {
    transform: translateX(0);
}

.hover-img {
    transform: translateX(100%);
}

.pc__img-wrapper:hover .main-img {
    transform: translateX(-100%);
}

.pc__img-wrapper:hover .hover-img {
    transform: translateX(0);
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
    overflow: hidden;
}
.sold-out-glass span {
    position: absolute;
    top: 50%;
    left: -30%;

    width: 160%;
    text-align: center;

    background: rgba(235, 8, 8, 0.75);
    color: #fff;

    padding: 12px 0;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 3px;

    transform: rotate(-25deg);
}
.modal-overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
}

.modal-content{
      width: 1100px;      
    max-width: 98%;     
    height: 85vh;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    animation: pop 0.25s ease;
}

@keyframes pop{
    from{transform: scale(0.9); opacity:0;}
    to{transform: scale(1); opacity:1;}
}

.close-btn{
    position: absolute;
    right: 18px;
    top: 12px;
    font-size: 26px;
    border: none;
    background: transparent;
    cursor: pointer;
    z-index: 10001;
}

.modal-body{
    display: flex;
    width: 100%;
}
.product-preview{
     width: 50%;
    padding: 25px;
    background: #f7f7f7;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.product-preview img{
    width: 100%;
    max-height: 5000px;
    object-fit: cover;
    border-radius: 12px;
}

/* RIGHT INFO */
.product-info{
    width: 50%;
    padding: 50px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.product-title{
    font-size: 20px;
    font-weight: 700;
}

.current-price{
    font-size: 18px;
    color: #eb1313;
    font-weight: 700;
}
.variant-section label{
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.swatch-group{
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.swatch-group button{
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    transition: 0.2s;
}

.swatch-group button:hover{
    border-color: #000;
}
.quantity-picker{
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn{
    width: 34px;
    height: 34px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
}

#quantityInput{
    width: 60px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.btn-buy-now{
    margin-top: auto;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg,#ff4b2b,#ff416c);
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.btn-buy-now:hover{
    transform: translateY(-2px);
}
.variant-btn{
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    transition: 0.2s;
}

.variant-btn.active{
    background: #ff416c;
    color: #fff;
    border-color: #ff416c;
}
.qty-buy-row{
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 10px;
}

.qty-buy-row .quantity-picker{
    display: flex;
    align-items: center;
    gap: 8px;
}
.qty-buy-row .btn-buy-now{
    height: 38px;
    padding: 0 18px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg,#ff4b2b,#ff416c);
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}
.qty-buy-row #quantityInput{
    width: 50px;
    text-align: center;
}
.qty-toast {
    margin-top: 6px;
    padding: 8px 10px;
    font-size: 13px;
    border-radius: 6px;
    text-align: center;
    display: none;
    background: #111;
    color: #e1d3d3;
}

.qty-toast.error {
    background: #e90707;
}

.qty-toast.success {
    background: #2ecc71;
}
.d-flex.text-warning i {
    color: #efac79 !important; 
    text-shadow: 0 1px 2px rgba(0,0,0,0.25);
}
</style>
<main class="pt-90">
    <section class="shop-main container-fluid d-flex pt-4 pt-xl-5 px-4 px-lg-5">
      <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
        <div class="aside-header d-flex d-lg-none align-items-center">
          <h3 class="text-uppercase fs-6 mb-0">Bộ Lọc</h3>
          <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
        </div>

        <div class="pt-4 pt-lg-0"></div>

        <div class="accordion" id="categories-list">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-1">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
                Danh Mục Sản Phẩm
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-1" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
              <form method="GET" action="{{ route('shop.index') }}">
              <div class="accordion-body px-0 pb-0 pt-3 category-list">

    @php
        $selectedCategories = request('categories');

        if (!is_array($selectedCategories)) {
            $selectedCategories = $selectedCategories
                ? explode(',', $selectedCategories)
                : [];
        }
    @endphp

    <ul class="list list-inline mb-0">
        @foreach ($categories as $category)
            <li class="list-item">

                <label class="menu-link py-1">

                    <input type="checkbox"
                           class="chk-category"
                           name="categories[]"
                           value="{{ $category->id }}"

                           {{ 
                               in_array($category->id, $selectedCategories) 
                               || request('category') == $category->id 
                               ? 'checked' : '' 
                           }}>

                    {{ $category->name }}
                </label>

                <span class="text-right float-end">
                    {{ $category->products->count() }}
                </span>

            </li>
        @endforeach
    </ul>
</div>
              </form>
            </div>
          </div>
        </div>
        <div class="accordion" id="brand-filters">
          <div class="accordion-item mb-4 pb-3">
            <h5 class="accordion-header" id="accordion-heading-brand">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-brand" aria-expanded="true" aria-controls="accordion-filter-brand">
                Thương Hiệu
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-brand" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-brand" data-bs-parent="#brand-filters">
              <div class="accordion-body px-0 pb-0">
              <ul class="list-inline brand-list">
                   @foreach ($brands as $brand)
                        <li class="list-item">
                         <span class="menu-link py-1">
                          <input type="checkbox" name="brands" value="{{ $brand->id }}" class="chk-brand"
                          @if(in_array($brand->id, explode(',', $f_brands))) checked ="checked" @endif >
                            {{ $brand->name }}
                            </span>
                             <span class="text-right float-end">
                              {{ $brand->products->count() }}
                                  </span>
                                   </li>
                                      @endforeach
              </ul>                      
              </div>
            </div>
          </div>
        </div>


        <div class="accordion" id="price-filters">
          <div class="accordion-item mb-4">
            <h5 class="accordion-header mb-2" id="accordion-heading-price">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-price" aria-expanded="true" aria-controls="accordion-filter-price">
                Giá Sản Phẩm
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
              <input class="price-range-slider" type="text" name="price_range" value="" data-slider-min="100"
                data-slider-max="10000000" data-slider-step="5" data-slider-value="[{{ (int)$min_price }},{{ (int)$max_price }}]" data-currency="" />
              <div class="price-range__info d-flex align-items-center mt-2">
                <div class="me-auto">
                  <span class="price-range__min">199.999 đ</span>
                </div>
                <div>
                  <span class="price-range__max">999.999 đ</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="shop-list flex-grow-1">
        <div class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split" data-settings='{
            "autoplay": {
              "delay": 4000
            },
            "slidesPerView": 1,
            "effect": "fade",
            "loop": true,
            "pagination": {
              "el": ".slideshow-pagination",
              "type": "bullets",
              "clickable": true
            }
          }'>
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                <div class="slide-split_text position-relative d-flex align-items-center"
                  style="background-color: #f5e6e0;">
                      <div class="slideshow-text container p-3 p-xl-5">
                        <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                           <strong>BRIJCLO</strong>
                        </h2>

                        <p class="mb-0 animate animate_fade animate_btt animate_delay-5">
                            Với BRIJCLO, phụ kiện không chỉ đơn thuần là một món đồ đi kèm trang phục mà còn là chi tiết 
                            giúp hoàn thiện phong cách cá nhân một cách tinh tế.  
                            Mỗi thiết kế được tạo ra với sự cân bằng giữa tính thẩm mỹ và tính ứng dụng, 
                            giúp bạn dễ dàng sử dụng trong nhiều hoàn cảnh khác nhau mỗi ngày.
                        </p>
                    </div>
                </div>
                <div class="slide-split_media position-relative">
                  <div class="slideshow-bg" style="background-color: #f5e6e0;">
                    <img loading="lazy" src="{{asset('/assets/images/shop/shop_banner1.webp')}}" width="630" height="450"
                      alt="BRIJCLO Accessories" class="slideshow-bg__img object-fit-cover" />
                  </div>
                </div>
              </div>
            </div>

            <div class="swiper-slide">
              <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                <div class="slide-split_text position-relative d-flex align-items-center"
                  style="background-color: #f5e6e0;">
                    <div class="slideshow-text container p-3 p-xl-5">
                          <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                             <strong>BRIJCLO</strong>
                          </h2>

                          <p class="mb-0 animate animate_fade animate_btt animate_delay-5">
                                  Các sản phẩm tại BRIJCLO được cập nhật o xu hướng thời trang hiện đại,  
                                  mang đến sự đa dạng trong lựa chọn từ đơn giản, nhẹ nhàng đến cá tính nổi bật.  
                                  Dù bạn theo đuổi phong cách nào, sản phẩm vẫn dễ dàng kết hợp với trang phục,  
                                  giúp tạo nên tổng thể hài hòa và thu hút ánh nhìn.

                          </p>.
                          </p>
                      </div>
                    </div>
                <div class="slide-split_media position-relative">
                  <div class="slideshow-bg" style="background-color: #f5e6e0;">
                   <img loading="lazy" src="{{ asset('assets/images/shop/shop_banner2.webp') }}" width="630" height="450"
                      alt="BRIJCLO Accessories" class="slideshow-bg__img object-fit-cover" />
                  </div>
                </div>
              </div>
            </div>

            <div class="swiper-slide">
              <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                <div class="slide-split_text position-relative d-flex align-items-center"
                  style="background-color: #f5e6e0;">
                    <div class="slideshow-text container p-3 p-xl-5">
                        <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                           <strong>BRIJCLO</strong>
                        </h2>

                        <p class="mb-0 animate animate_fade animate_btt animate_delay-5">
                          BRIJCLO hướng đến việc mang lại trải nghiệm thời trang tiện lợi và linh hoạt hơn mỗi ngày.  
                        Không cần quá cầu kỳ, chỉ cần một vài chi tiết phụ kiện phù hợp là đủ để tạo điểm nhấn.  
                        Chúng tôi tin rằng phong cách đến từ sự tự tin và những lựa chọn đơn giản nhưng đúng chất.  
                        Và BRIJCLO là nơi giúp bạn bắt đầu điều đó.
                    </div>
                </div>
                <div class="slide-split_media position-relative">
                  <div class="slideshow-bg" style="background-color: #f5e6e0;">
                    <img loading="lazy" src="{{asset('assets/images/shop/shop_banner3.webp')}}" width="630" height="450"
                      alt="BRIJCLO Accessories" class="slideshow-bg__img object-fit-cover" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="container p-3 p-xl-5">
            <div class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 mb-4 pb-xl-2"></div>

          </div>
        </div>

        <div class="mb-3 pb-2 pb-xl-3"></div>

        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="{{route('home.index')}}" class="menu-link menu-link_us-s text-uppercase fw-medium">Trang Chủ</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Mua Sắm</a>
            
          </div>

          <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Page size" id="pagesize"name="pagesize" style="margin-right-right:20px;">
              <option value="12" {{ $size==12 ? 'selected':'' }}>Thời Gian</option>
               <option value="24" {{ $size==24 ? 'selected':'' }}>24h</option>
               <option value="48" {{ $size==48  ? 'selected':'' }}>48h</option>
               <option value="102" {{ $size==102 ? 'selected':'' }}>102h</option>
            </select>
            <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Sort Items" name="orderby" id="orderby"
              name="total-number">
              <option value="-1" {{ $order== -1 ? 'selected':'' }}>Mặc định</option>
              <option value="1" {{ $order== 1 ? 'selected':'' }}>Mới nhất</option>
              <option value="2" {{ $order== 2 ? 'selected':'' }}>Cũ nhất</option>
              <option value="3" {{ $order== 3 ? 'selected':'' }}>Giá tăng dần</option>
              <option value="4" {{ $order== 4 ? 'selected':'' }}>Giá giảm dần</option>
            </select>

            <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>

            <div class="col-size align-items-center order-1 d-none d-lg-flex">
              <span class="text-uppercase fw-medium me-2">Trang</span>
              <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="2">2</button>
              <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="3">3</button>
              <button class="btn-link fw-medium js-cols-size" data-target="products-grid" data-cols="4">4</button>
            </div>

            <div class="shop-filter d-flex align-items-center order-0 order-md-3 d-lg-none">
              <button class="btn-link btn-link_f d-flex align-items-center ps-0 js-open-aside" data-aside="shopFilter">
                <svg class="d-inline-block align-middle me-2" width="14" height="10" viewBox="0 0 14 10" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_filter" />
                </svg>
                <span class="text-uppercase fw-medium d-inline-block align-middle">Bộ Lọc</span>
              </button>
            </div>
          </div>
        </div>

      <div class="products-grid row row-cols-2 row-cols-md-3 row-cols-lg-4" id="products-grid">
@foreach($products as $product)
<div class="product-card-wrapper col">
  <div class="product-card mb-3 mb-md-4 mb-xxl-5 position-relative h-100">
     @if($product->sale_price && $product->regular_price > 0)
    @php
      $discount = round(100 - ($product->sale_price / $product->regular_price * 100));
    @endphp
    <span class="discount-badge">-{{ $discount }}%</span>
  @endif
    <div class="pc__img-wrapper position-relative">
      <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
  <div class="swiper-wrapper">

    @php
      $images = !empty($product->images)
          ? array_values(array_filter(array_map('trim', explode(',', $product->images))))
          : [];
    @endphp

    <div class="swiper-slide">
      <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}">

        <div class="pc__img-wrapper">

          <img
            src="{{ asset('uploads/products/'.$product->image) }}"
            width="330" height="400"
            class="pc__img main-img">

          @if(!empty($images[0]))
            <img
              src="{{ asset('uploads/products/'.trim($images[0])) }}"
              width="330" height="400"
              class="pc__img hover-img">
          @endif

        </div>

      </a>
    </div>
    @foreach($images as $key => $img)
      @if($key > 0)
      <div class="swiper-slide">
        <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}">
          <img loading="lazy"
               src="{{ asset('uploads/products/'.trim($img)) }}"
               width="330" height="400"
               class="pc__img">
        </a>
      </div>
      @endif
    @endforeach

  </div>
</div>
 @if($product->is_out_of_stock)
    <div class="sold-out-glass">
        <span>HẾT HÀNG</span>
    </div>

@elseif(Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
    <a href="{{ route('cart.index') }}"
       class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium"
       style="background-color:#ffc;">
       Đến Giỏ Hàng
    </a>
@else
@php
    $sizeOptions = $product->variants
        ->where('quantity', '>', 0)
        ->whereNotNull('size_id')
        ->pluck('size.name')
        ->filter()
        ->unique()
        ->values()
        ->all();

    if (empty($sizeOptions)) {
        $rawSizes = is_array($product->sizes)
            ? $product->sizes
            : (json_decode($product->sizes, true) ?? []);

        $sizeOptions = collect($rawSizes)
            ->map(function ($item) {
                if (is_array($item)) {
                    return $item['size'] ?? $item['label'] ?? null;
                }
                if (is_object($item)) {
                    return $item->size ?? $item->label ?? null;
                }
                return $item;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    $colorOptions = $product->variants
        ->where('quantity', '>', 0)
        ->whereNotNull('color_id')
        ->pluck('color.name')
        ->filter()
        ->unique()
        ->values()
        ->all();

    if (empty($colorOptions)) {
        $rawColors = is_array($product->colors)
            ? $product->colors
            : (json_decode($product->colors, true) ?? []);

        $colorOptions = collect($rawColors)
            ->map(function ($item) {
                if (is_array($item) || is_object($item)) {
                    return $item['color'] ?? $item->color ?? $item['label'] ?? null;
                }
                return $item;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
@endphp
<button 
    type="button"
    class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium buy-now-btn"
    data-id="{{ $product->id }}"
    data-name="{{ $product->name }}"
    data-regular="{{ $product->regular_price }}"
    data-sale="{{ $product->sale_price }}"
    data-image="{{ asset('uploads/products/'.$product->image) }}"
    data-rating="{{ $product->rating }}"
    data-reviews="{{ $product->reviews_count }}"
    data-description="{{ $product->short_description }}"
    data-sizes='@json($sizeOptions)'
    data-colors='@json($colorOptions)'
    data-variants='@json($product->variants)'
>
    Mua Ngay
</button>
@endif
    </div>

    <div class="pc__info">

  <div class="d-flex justify-content-between align-items-center">
    
    <p class="pc__category mb-0">
      {{$product->category->name ?? 'No category'}}
    </p>

    <div class="wishlist-box">
      @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)

        <form method="POST"
              action="{{ route('wishlist.item.remove',['rowId'=>Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}">
          @csrf
          @method('DELETE')

          <button type="submit"
                  class="pc__btn-wl filled-heart border-0 bg-transparent"
                  title="Bỏ khỏi wishlist">
            <svg width="16" height="16">
              <use href="#icon_heart" />
            </svg>
          </button>
        </form>

      @else

        <form method="POST" action="{{ route('wishlist.add') }}">
          @csrf
          <input type="hidden" name="id" value="{{ $product->id }}" />
          <input type="hidden" name="name" value="{{ $product->name }}" />
          <input type="hidden" name="price"
                 value="{{ $product->sale_price == '' ? $product->regular_price : $product->sale_price }}" />
          <input type="hidden" name="quantity" value="1" />

          <button type="submit"
                  class="pc__btn-wl border-0 bg-transparent"
                  title="Thêm wishlist">
            <svg width="16" height="16">
              <use href="#icon_heart" />
            </svg>
          </button>
        </form>

      @endif
    </div>

  </div>

  <h6 class="pc__title mt-1">
    <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}">
      {{$product->name}}
    </a>
  </h6>

  <div class="product-card__price d-flex">
    <span class="money price">
      @if($product->sale_price)
        <s>{{ number_format($product->regular_price, 0, ',', '.') }} đ</s> 
        {{ number_format($product->sale_price, 0, ',', '.') }} đ
      @else
        {{ number_format($product->regular_price, 0, ',', '.') }} đ
      @endif
    </span>
  </div>

  @php
              $rating = $product->rating ?? 0;
          @endphp

          <div class="d-flex align-items-center gap-1 mt-1">

   <div class="d-flex text-warning">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= floor($rating))
            <i class="fa fa-star"></i>
        @elseif ($i - $rating < 1 && $i - $rating > 0)
            <i class="fa fa-star-half-o"></i>
        @else
            <i class="fa fa-star-o"></i>
        @endif
    @endfor
</div>

            <span class="text-muted small">
              {{ number_format($rating, 1) }} · {{ $product->reviews_count }} đánh giá
            </span>

          </div>

          </div>

            </div>
          </div>
          @endforeach
          </div>

      <div class="divider"></div>
<div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
</div>
      </div>
    </section>
  </main>

  <form id="frmfilter" method="GET" action="{{ route('shop.index') }}">
    <input type="hidden" name="page" value="{{ $products->currentPage() }}">
    <input type="hidden" name="size" id="size" value="{{ $size }}" />
    <input type="hidden" name="order" id="order" value="{{ $order }}" />
     <input type="hidden" name="brands" id="hdnBrands" />
     <input type="hidden" name="categories" id="hdnCategories" />
     <input type="hidden" name="min_price" id="hdnMinPrice" value="{{ $min_price }}"/>
      <input type="hidden" name="max_price" id="hdnMaxPrice" value="{{ $max_price }}"/>
</form>

<div id="buyNowModal" class="modal-overlay">

    <div class="modal-content">

        <button type="button" id="closeModal" class="close-btn">&times;</button>

        <div class="modal-body">

            <div class="product-preview">
                <img id="modalImage" src="" alt="Product">

    
                           </div>

            <div class="product-info">

                <h4 id="modalName" class="product-title"></h4>

                <div class="rating">
                    <span id="modalStars"></span>
                    <span id="modalReviewCount"></span>
                </div>

                <div class="product-single__price">
                    <span id="modalRegularPrice" class="old-price"></span>
                    <span id="modalSalePrice" class="current-price"></span>
                </div>

                <p id="modalDescription" class="product-desc"></p>

                <form action="{{ route('buy.now') }}" method="POST" id="buyNowForm">
                    @csrf

                    <input type="hidden" name="id" id="modalId">
                    <input type="hidden" name="size" id="selectedSize">
                    <input type="hidden" name="color" id="selectedColor">

                    <div class="variant-section">
                        <label>Kích thước</label>
                        <div class="swatch-group" id="sizeOptions"></div>
                    </div>
                    <div id="sizeError" style="color:red; margin-top:10px; display:none;">
                       Vui lòng chọn Size
                  </div>


                    <div class="variant-section">
                        <label>Màu sắc</label>
                        <div class="swatch-group" id="colorOptions"></div>
                    </div>
                    <div id="colorError" style="color:red; margin-top:5px; display:none;">
                        Vui lòng chọn Màu sắc
                    </div>

                    <!-- QTY -->
               <div class="qty-section">
    <div class="qty-title">
        Số lượng
    </div>
    <div class="qty-buy-row">
        <div class="quantity-picker">
            <button type="button" class="qty-btn minus">-</button>
            <input type="number"
                   name="quantity"
                   id="quantityInput"
                   value="1"
                   min="1"
                   readonly>
            <button type="button" class="qty-btn plus">+</button>
        </div>
        <button type="submit" class="btn-buy-now">
            Mua ngay
        </button>
    </div>
    <div id="qtyToast" class="qty-toast"></div>
</div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  let maxStock = 0;
  const modal = document.getElementById('buyNowModal');
  const closeBtn = modal ? modal.querySelector('#closeModal') : null;
  const qtyInput = document.getElementById('quantityInput');
  const plusBtn = document.querySelector('.qty-btn.plus');
  const minusBtn = document.querySelector('.qty-btn.minus');
  const sizeBox = document.getElementById('sizeOptions');
  const colorBox = document.getElementById('colorOptions');

  const setModalVisible = (visible) => {
      if (!modal) return;
      modal.style.display = visible ? 'flex' : 'none';
  };

  const parseVariantValues = (rawData) => {
      if (!rawData) {
          return [];
      }

      let values = [];
      try {
          values = JSON.parse(rawData);
      } catch (e) {
          values = rawData.split(',');
      }

      return values
          .map(item => {
              if (item && typeof item === 'object') {
                  return String(item.size || item.name || item.label || item.value || '').trim();
              }
              return String(item || '').trim();
          })
          .filter(v => v.length > 0);
  };

  const renderVariantButtons = (container, values, onSelect) => {
      if (!container) return;
      container.innerHTML = '';

      values.forEach(value => {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'variant-btn';

          const label = String(value).trim();
          button.innerText = label;

          button.addEventListener('click', function () {
              container.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
              button.classList.add('active');
              onSelect(label);
          });

          container.appendChild(button);
      });
  };
  function updateStock() {
    const size = document.getElementById('selectedSize')?.value;
    const color = document.getElementById('selectedColor')?.value;

    const qtyInput = document.getElementById('quantityInput');
    const qtyError = document.getElementById('qtyError');

    if (!window.currentVariants) return;

    const found = window.currentVariants.find(v =>
        (!size || (v.size?.name ?? v.size) == size) &&
        (!color || (v.color?.name ?? v.color) == color)
    );

    maxStock = found ? parseInt(found.quantity) : 0;

    if (maxStock <= 0) {
        qtyInput.value = 0;
        if (qtyError) {
            qtyError.style.display = "block";
            qtyError.innerText = "Biến thể này đã hết hàng 😢";
        }
        return;
    }

    if (qtyError) qtyError.style.display = "none";

    if (parseInt(qtyInput.value) > maxStock) {
        qtyInput.value = maxStock;
    }
}

  const fillModal = (target) => {
      if (!target) return;

      const modalIdInput = document.getElementById('modalId');
      const modalNameEl = document.getElementById('modalName');
      const modalImage = document.getElementById('modalImage');
      const regularEl = document.getElementById('modalRegularPrice');
      const saleEl = document.getElementById('modalSalePrice');
      const selectedSize = document.getElementById('selectedSize');
      const selectedColor = document.getElementById('selectedColor');
      const starsEl = document.getElementById('modalStars');
      const reviewEl = document.getElementById('modalReviewCount');
      const descEl = document.getElementById('modalDescription');
      const variants = target.dataset.variants ? JSON.parse(target.dataset.variants) : [];
       window.currentVariants = variants;

      if (modalIdInput) modalIdInput.value = target.dataset.id || '';
      if (modalNameEl) modalNameEl.innerText = target.dataset.name || '';
      if (modalImage) modalImage.src = target.dataset.image || '';
      if (qtyInput) qtyInput.value = 1;
      if (selectedSize) selectedSize.value = '';
      if (selectedColor) selectedColor.value = '';

      const regular = parseInt(target.dataset.regular, 10) || 0;
      const sale = target.dataset.sale ? parseInt(target.dataset.sale, 10) : null;

      if (regularEl && saleEl) {
          if (sale && sale < regular) {
              regularEl.innerHTML = `<s>${regular.toLocaleString('vi-VN')} đ</s>`;
              saleEl.innerHTML = `${sale.toLocaleString('vi-VN')} đ`;
          } else {
              regularEl.innerHTML = '';
              saleEl.innerHTML = `${regular.toLocaleString('vi-VN')} đ`;
          }
      }

      const sizes = parseVariantValues(target.dataset.sizes || '[]');
     renderVariantButtons(sizeBox, sizes, value => {
    if (selectedSize) selectedSize.value = value;
    updateStock();
});

      const colors = parseVariantValues(target.dataset.colors || '[]');
      renderVariantButtons(colorBox, colors, value => {
    let label = value;
    const map = { do:'Đỏ', den:'Đen', xanh:'Xanh' };
    label = map[label.toLowerCase()] || label;

    if (selectedColor) selectedColor.value = label;
    updateStock();
});

      if (starsEl && reviewEl) {
          const rating = parseFloat(target.dataset.rating) || 0;
          const reviews = parseInt(target.dataset.reviews, 10) || 0;
          let stars = '';

          for (let i = 1; i <= 5; i++) {
              stars += i <= Math.floor(rating) ? '⭐' : '☆';
          }

          starsEl.innerText = stars;
          reviewEl.innerText = `${rating.toFixed(1)} · ${reviews} đánh giá`;
      }

      if (descEl) {
          descEl.innerHTML = target.dataset.description || 'Chưa có mô tả sản phẩm';
      }
  };

  if (closeBtn) {
      closeBtn.addEventListener('click', function (e) {
          e.preventDefault();
          setModalVisible(false);
      });
  }

  document.addEventListener('click', function (e) {
      if (e.target && e.target.matches && e.target.matches('#closeModal')) {
          e.preventDefault();
          setModalVisible(false);
      }
  });

  if (modal) {
      modal.addEventListener('click', function (e) {
          if (e.target === modal || !e.target.closest('.modal-content')) {
              setModalVisible(false);
          }
      });
  }

  window.addEventListener('keyup', function (e) {
      if (e.key === 'Escape') {
          setModalVisible(false);
      }
  });

  if (plusBtn && qtyInput) {
    plusBtn.addEventListener('click', function () {
        let current = parseInt(qtyInput.value, 10) || 1;

        if (current >= maxStock) {
            showToast(`Chỉ còn ${maxStock} sản phẩm trong kho `);
            return;
        }

        qtyInput.value = current + 1;
    });
}

  if (minusBtn && qtyInput) {
      minusBtn.addEventListener('click', function () {
          const value = parseInt(qtyInput.value, 10) || 1;
          if (value > 1) {
              qtyInput.value = value - 1;
          }
      });
  }

 document.querySelectorAll('.buy-now-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        fillModal(this);
        setModalVisible(true);
    });
});

  document.querySelectorAll('.js-swiper-slider').forEach(function (el) {

    let settings = {};

    try {
      const raw = el.getAttribute('data-settings');
      if (raw) settings = JSON.parse(raw);
    } catch (e) {
      console.warn('Swiper JSON lỗi:', e);
    }

    new Swiper(el, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 10,
      observer: true,
      observeParents: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      ...settings
    });

  });

});
  $(function() {
      $("#pagesize").on("change", function() {
          $("#size").val($(this).val());
          setTimeout(() => {
              $("#frmfilter").submit();
          }, 50);
      });

      $("#orderby").on("change", function() {
          $("#order").val($(this).val());
          setTimeout(() => {
              $("#frmfilter").submit();
          }, 50);
      });

      $("input[name='brands']").on("change", function() {
          var brands = "";
          $("input[name='brands']:checked").each(function() {
              brands += (brands === "" ? "" : ",") + $(this).val();
          });
          $("#hdnBrands").val(brands);
          setTimeout(() => {
              $("#frmfilter").submit();
          }, 50);
      });
      $("input[name='categories[]']").on("change", function() {
    var categories = "";
    $("input[name='categories[]']:checked").each(function() {
        categories += (categories === "" ? "" : ",") + $(this).val();
    });
    $("#hdnCategories").val(categories);
    setTimeout(() => {
        $("#frmfilter").submit();
    }, 50);
});

      $(".price-range-slider").on("slideStop", function(e) {
          var value = e.value;
          console.log("Selected price range: ", value);
          if (Array.isArray(value)) {
              $("#hdnMinPrice").val(value[0]);
              $("#hdnMaxPrice").val(value[1]);
          } else {
              var parts = value.toString().split(',');
              if (parts.length === 2) {
                  $("#hdnMinPrice").val(parseInt(parts[0]));
                  $("#hdnMaxPrice").val(parseInt(parts[1]));
              }
          }
          $("#frmfilter").submit();
      });
  });
  const form = document.getElementById('buyNowForm');
  const sizeError = document.getElementById('sizeError');
const colorError = document.getElementById('colorError');

if (form) {
    form.addEventListener('submit', function (e) {

        const size = document.getElementById('selectedSize')?.value;
        const color = document.getElementById('selectedColor')?.value;

        const sizeError = document.getElementById('sizeError');
        const colorError = document.getElementById('colorError');

        sizeError.style.display = 'none';
        colorError.style.display = 'none';

        let hasError = false;

        if (!size) {
            sizeError.style.display = 'block';
            hasError = true;
        }

        if (!color) {
            colorError.style.display = 'block';
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            return;
        }
    });
}
if (form) {
    form.addEventListener('submit', function (e) {
        const qty = parseInt(qtyInput.value) || 0;

        const size = document.getElementById('selectedSize')?.value;
        const color = document.getElementById('selectedColor')?.value;

        sizeError.style.display = 'none';
        colorError.style.display = 'none';

        let hasError = false;

        if (!size) {
            sizeError.style.display = 'block';
            hasError = true;
        }

        if (!color) {
            colorError.style.display = 'block';
            hasError = true;
        }

        if (qty > maxStock) {
            showToast(`Chỉ còn ${maxStock} sản phẩm thôi nha 😭`);
            hasError = true;
        }

        if (hasError) e.preventDefault();
    });
}
function showToast(msg, type = "error") {
    const t = document.getElementById("qtyToast");
    if (!t) return;

    t.innerText = msg;
    t.style.display = "block";

       t.classList.remove("error", "success");
    t.classList.add(type);

    clearTimeout(t._timer);

    t._timer = setTimeout(() => {
        t.style.display = "none";
    }, 2000);
}
        
</script>
@endpush