@extends('layouts.app')
@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>

  <section class="my-account container">
    <h2 class="page-title">Danh sách yêu thích</h2>

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

                    <div class="swiper-container background-img js-swiper-slider"
                         data-settings='{"resizeObserver": true}'>

                      <div class="swiper-wrapper">

                        {{-- IMAGE CHÍNH --}}
                        <div class="swiper-slide">
                          <img loading="lazy"
                        src="{{ !empty($item->model?->image) 
                              ? '/uploads/products/'.$item->model->image 
                              : '' }}"
                        width="330" height="400"
                        class="pc__img">
                        </div>

                        {{-- IMAGE PHỤ --}}
                        @if(!empty($item->model?->images))
                          <div class="swiper-slide">
                            <img loading="lazy"
                                 src="{{ asset('uploads/products/'.$item->model->images) }}"
                                 width="330" height="400"
                                 class="pc__img">
                          </div>
                        @endif

                      </div>

                    </div>

                    {{-- REMOVE --}}
                    <form method="POST"
                          action="{{ route('wishlist.item.remove', $item->rowId) }}">
                      @csrf
                      @method('DELETE')

                      <button class="btn-remove-from-wishlist">✕</button>
                    </form>
                    </a>

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
                <p>Chưa có sản phẩm nao được yêu thích</p>
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