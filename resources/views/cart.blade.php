@extends('layouts.app')
@section('content')
<style>
    .text-success {
        color: #278c04 !important;
    }
    .text-danger {
    color: #d61808 !important;    
}

.qty-control:focus-within {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Giỏ Hàng</h2>
      <div class="checkout-steps">
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Giỏ hàng</span>
            <em>Quản lý danh sách sản phẩm</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
           <span>Thanh toán</span>
            <em>Tiến hành đặt hàng</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Xác nhận</span>
            <em>Kiểm tra và gửi đơn hàng</em>
          </span>
        </a>
      </div>
      <div class="shopping-cart">
        @if(Cart::instance('cart')->count() > 0)
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
               <th>Sản phẩm</th>
                <th>Hình ảnh</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tạm tính</th>
                <th>Xóa</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach (Cart::instance('cart')->content() as $item)
              <tr>
                <td>
                  <div class="shopping-cart__product-item">
                    <img loading="lazy" src="{{asset('uploads/products/thumbnails')}}/{{$item->model->image}}" width="120" height="120" alt="{{$item->name}}" />
                  </div>
                </td>
                <td>
                  <div class="shopping-cart__product-item__detail">
                    <h4>{{$item->name}}</h4>
                    <ul class="shopping-cart__product-item__options">
                      <li>Màu Sắc: {{ $item->options['color'] ?? '-' }}</li>
                      <li>Size: {{ $item->options['size'] ?? '-' }}</li>
                    </ul>
                  </div>
                </td>
                <td>
                  <span class="shopping-cart__product-price">{{ number_format($item->price, 0, ',', '.') }} đ</span>
                </td>
                <td>

          {{-- <div class="qty-control position-relative qty-initialized">
                      
                      <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center" fdprocessedid="6bt6ff" data-has-listeners="true">
                      <div class="qty-control__reduce">-</div>
                      <div class="qty-control__increase">+</div>
                  </div> --}}

                  <div class="qty-control d-flex align-items-center">
                    <form method="POST" action="{{ route('cart.qty.decrease', ['rowId' => $item->rowId]) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="qty-btn">-</button>
                    </form>

                    <input type="text" value="{{ $item->qty }}" readonly class="qty-input">
                    
                      <form method="POST" action="{{ route('cart.qty.increase', ['rowId' => $item->rowId]) }}">
                          @csrf
                          @method('PUT')
                          <button type="submit" class="qty-btn">+</button>
                      </form>

                  </div> 

                </td>
                <td>
                  <span class="shopping-cart__subtotal">{{ number_format($item->subtotal, 0, ',', '.') }} đ</span>
                </td>
                <td>
                  <form method="POST" action="{{ route('cart.item.remove', ['rowId' => $item->rowId]) }}">
                   @csrf
                   @method('DELETE')
                  <a href="javascript:void(0)" class="remove-cart">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                      <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                    </svg>
                  </a>
                  </form>
                </td>
              </tr>
               @endforeach
              
            </tbody>
          </table>
          <div class="cart-table-footer"> 

            @if(!Session::has('coupon'))
            <form action="{{route('cart.coupon.apply')}}" method="POST" class="position-relative bg-body">
              @csrf
              <input class="form-control" type="text" name="coupon_code" placeholder="Mã Giảm Giá" value="">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="ÁP DỤNG MÃ">
          </form>   
            @else
            <form action="{{route('cart.coupon.remove')}}" method="POST" class="position-relative bg-body">
              @csrf
              @method('DELETE')
              <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="@if(Session::has('coupon')) {{Session::get('coupon')['code']}} Applied! @endif">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="XÓA MÃ GIẢM GIÁ">
          </form>   
          @endif
          <form method="POST" action="{{ route('cart.empty') }}">
             @csrf
               @method('DELETE')
              <button type="submit" class="btn btn-light">XÓA TOÀN BỘ GIỎ HÀNG</button>
                 </form>
          </div>
          <div>
             @if(Session::has('success'))
             <p class="text-success">{{ Session::get('success') }}</p>
             @elseif(Session::has('error'))
                 <p class="text-danger">{{ Session::get('error') }}</p>
             @endif
          </div>
        </div>
        <div class="shopping-cart__totals-wrapper">
          <div class="sticky-content">
           <div class="shopping-cart__totals">
                    <h3>Tổng Giỏ Hàng</h3>

                    @if(Session::has('discounts'))
                        <table class="cart-totals">
                            <tbody>
                                <tr>
                                    <th>Tạm Tính</th>
                                    <td class="text-right">
                                        {{ number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0, ',', '.') }} đ
                                    </td>
                                </tr>

                                <tr>
                                    <th>Giảm Giá {{ Session::get('coupon')['code'] ?? '' }}</th>
                                    <td class="text-success text-right">
                                        -{{ number_format(Session::get('discounts')['discount'] ?? 0, 0, ',', '.') }} đ
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tạm Tính Sau Giảm</th>
                                    <td class="text-right">
                                        {{ number_format(Session::get('discounts')['subtotal'] ?? 0, 0, ',', '.') }} đ
                                    </td>
                                </tr>

                                <tr>
                                    <th>Phí Vận Chuyển</th>
                                    <td class="text-right">Miễn Phí</td>
                                </tr>

                                <tr>
                                    <th>Thuế</th>
                                    <td class="text-right">
                                        {{ number_format(Session::get('discounts')['tax'] ?? 0, 0, ',', '.') }} đ
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tổng Cộng</th>
                                    <td class="text-right">
                                        <strong>
                                            {{ number_format(Session::get('discounts')['total'] ?? 0, 0, ',', '.') }} đ
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="cart-totals">
                            <tbody>
                                <tr>
                                    <th>Tạm Tính</th>
                                    <td>
                                        {{ number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0, ',', '.') }} đ
                                    </td>
                                </tr>

                                <tr>
                                    <th>Phí Vận Chuyển</th>
                                    <td>Miễn Phí</td>
                                </tr>

                                <tr>
                                    <th>Thuế</th>
                                    <td>
                                        {{ number_format((float) str_replace(',', '', Cart::instance('cart')->tax()), 0, ',', '.') }} đ
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tổng Cộng</th>
                                    <td>
                                        {{ number_format((float) str_replace(',', '', Cart::instance('cart')->total()), 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="mobile_fixed-btn_wrapper">
                    <div class="button-wrapper container">
                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-checkout">
                            Tiến Hành Thanh Toán
                        </a>
                    </div>
                </div>
        </div>
        @else
        <div class="row">
           <div class="col-md 12 text-center-center pt-5 bp-5">
            <p>Chưa Có Sản Phẩm Nào Trong Giỏ Hàng</p>
            <a href="{{route('shop.index')}}" class="btn btn-info">Mua Sắm Ngay</a>
        </div>

        @endif
      </div>
    </section>
  </main>
@endsection
 
@push('scripts') 
<script>
    $(function() {
        $('.remove-cart').on('click', function(e) {
            e.preventDefault(); 
            $(this).closest('form').submit();
        });
        $('.qty-control__increase, .qty-btn').on('click', function() {
            $(this).closest('form').submit();
        });

        $('.qty-control__reduce, .qty-btn').on('click', function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endpush