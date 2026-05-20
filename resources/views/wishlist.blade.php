@extends('layouts.app')
@section('content')
<style>
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
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 3px;

    transform: rotate(-25deg);
}
    .cart-header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 40px;
}

.btn-back{
    padding: 10px 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: 0.2s;
    font-weight: 500;
}

.btn-back:hover{
    background: #f3f3f3;
    transform: translateX(-2px);
}
</style>
<main class="pt-90">
  <div class="mb-4 pb-4"></div>

  <section class="my-account container">
    
                  <div class="cart-header">
     <h2 class="page-title">Danh sách yêu thích</h2>

    <a href="{{ route('user.index') }}" class="btn-back">
        Quay lại
    </a>
</div>

    <div class="row">

      {{-- LEFT MENU --}}
      <div class="col-lg-3">
        <ul class="account-nav">
          <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s">Bảng điều khiển</a></li>
          <li><a href="{{route('user.orders')}}" class="menu-link menu-link_us-s">Đơn hàng</a></li>
          <li><a href="{{route('user.address')}}" class="menu-link menu-link_us-s">Địa chỉ</a></li>
          <li><a href="{{route('user.details')}}" class="menu-link menu-link_us-s">Thông tin tài khoản</a></li>
          <li><a href="{{route('wishlist.index')}}" class="menu-link menu-link_us-s menu-link_active">Danh sách yêu thích</a></li>
           <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-link menu-link_us-s" style="border:none;background:none;">
                    ĐĂNG XUẤT
                    </button>
                </form>
            </li>
        </ul>
        
      </div>

      {{-- RIGHT CONTENT --}}
      <div class="col-lg-9">
        <div class="page-content my-account__wishlist">

          <div class="products-grid row row-cols-2 row-cols-lg-3" id="products-grid">

            @forelse($items as $item)

              <div class="product-card-wrapper">
                <div class="product-card mb-3 mb-md-4 mb-xxl-5">

                  {{-- IMAGE --}}
                 <div class="pc__img-wrapper">

                    <a href="{{ route('shop.product.details', ['product_slug' => $item->model?->slug]) }}">

                      @php
                        $images = [];

                        if(!empty($item->model?->images)){
                            $images = json_decode($item->model->images, true);

                            if(!$images){
                                $images = explode(',', $item->model->images);
                            }
                        }
                      @endphp

                      {{-- ảnh chính --}}
                      <img loading="lazy"
                          src="{{ !empty($item->model?->image) 
                                ? asset('uploads/products/'.$item->model->image) 
                                : '' }}"
                          width="330" height="400"
                          class="pc__img main-img">

                      {{-- ảnh hover --}}
                      @if(!empty($images) && !empty($images[0]))
                        <img loading="lazy"
                            src="{{ asset('uploads/products/'.trim($images[0])) }}"
                            width="330" height="400"
                            class="pc__img hover-img">
                      @endif

                    </a>

                    {{-- badge --}}
                    @if($item->model?->sale_price && $item->model?->regular_price > 0)
                      @php
                        $discount = round(100 - ($item->model->sale_price / $item->model->regular_price * 100));
                      @endphp
                      <span class="discount-badge">-{{ $discount }}%</span>
                    @endif
                      @if($item->model?->is_out_of_stock)
                          <div class="sold-out-glass">
                              <span>HẾT HÀNG</span>
                          </div>
                      @endif

                    {{-- remove --}}
                    <form method="POST" action="{{ route('wishlist.item.remove', $item->rowId) }}">
                      @csrf
                      @method('DELETE')
                      <button class="btn-remove-from-wishlist">✕</button>
                    </form>

                  </div>

                  {{-- INFO --}}
                  <div class="pc__info position-relative">

                    <p class="pc__category">
                      {{ $item->model?->category?->name ?? 'Không phân loại' }}
                    </p>

                    <h6 class="pc__title">
                      {{ $item->model?->name ?? $item->name }}
                    </h6>

                    <div class="product-card__price d-flex">
                      <span class="money price">
                        {{ number_format($item->price, 0, ',', '.') }} đ
                      </span>
                    </div>

                  </div>

                </div>
              </div>

            @empty
              <div class="col-12">
                <p>Chưa có sản phẩm nào được yêu thích</p>
              </div>
            @endforelse

          </div>

        </div>
      </div>

    </div>
  </section>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.js-swiper-slider').forEach(function (el) {
    new Swiper(el, {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 10,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: el.querySelector('.swiper-pagination'),
        clickable: true,
      },
    });
  });
});
</script>
@endsection