@extends('layouts.app')
@section('content')
  <style>
    .text-success {
      color: #278c04 !important;
    }
.text-danger {
    color: #ff0000 !important;
    font-weight: 600;
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
    <section class="shop-checkout container">
      <div class="cart-header">
    <h2 class="page-title">Thanh Toán & Giao Hàng</h2>

    <a href="{{ route('cart.index') }}" class="btn-back">
        Quay lại
    </a>
</div>
      <div class="checkout-steps">
        <a href="{{route('cart.index')}}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Giỏ hàng</span>
            <em>Quản lý sản phẩm của bạn</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
           <span>Thanh toán</span>
          <em>Điền thông tin giao hàng</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Xác nhận</span>
            <em>Kiểm tra và đặt đơn</em>
          </span>
        </a>
      </div>
      <form name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
        <input type="hidden" name="address_id" id="address_id_input" value="{{ $address->id ?? '' }}">
        @csrf
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row">
              <div class="col-6">
                <h4>THÔNG TIN GIAO HÀNG</h4>
              </div>
              <div class="col-6">
              </div>
            </div>
            @if($address)
              <div class="row">
                <div class="col-md-12">
                  <div class="my-account__address-list">
                    <div class="my-account__address-list-item">
                      <div class="my-account__address-item__detail">
                         <p><strong>Họ tên:</strong> {{ $address->name ?? '---' }}</p>

                            <p><strong>Địa chỉ:</strong> 
                                {{ $address->address ?? '---' }}
                            </p>

                            <p><strong>Khu vực:</strong> 
                                {{ $address->locality ?? '---' }}
                            </p>

                            <p><strong>Thành phố:</strong> 
                                {{ $address->city ?? '---' }}
                            </p>

                            @if($address->landmark)
                                <p><strong>Ghi chú:</strong> {{ $address->landmark }}</p>
                            @else
                                <p><strong>Ghi chú:</strong> ---</p>
                            @endif

                            <p><strong>Mã bưu điện (ZIP):</strong> {{ $address->zip ?? '---' }}</p>
                            
                            <p><strong>Số điện thoại:</strong> 
                                <span class="text-dark">{{ $address->phone ?? '---' }}</span>
                            </p>                    
                      </div>
                    </div>
                  </div>
                  <div class="mt-3">
                      <button type="button"
                          class="btn btn-outline-primary btn-sm"
                          data-bs-toggle="modal"
                          data-bs-target="#addressModal">
                          Thay đổi địa chỉ
                      </button>
                  </div>
                </div>
              </div>
                          <div class="modal fade" id="addressModal" tabindex="-1">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">Chọn địa chỉ giao hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">

                  @foreach($addresses as $item)
                    <div class="border p-3 mb-2 rounded">
                      <div class="form-check">
                        <input class="form-check-input select-address"
                              type="radio"
                              name="selected_address"
                              value="{{ $item->id }}"
                              data-name="{{ $item->name }}"
                              data-phone="{{ $item->phone }}"
                              data-address="{{ $item->address }}"
                              data-city="{{ $item->city }}"
                              data-locality="{{ $item->locality }}"
                              data-zip="{{ $item->zip }}"
                              data-landmark="{{ $item->landmark }}">

                        <label class="form-check-label w-100">
                          <strong>{{ $item->name }}</strong> - {{ $item->phone }} <br>
                          {{ $item->address }}, {{ $item->locality }}, {{ $item->city }}
                        </label>
                      </div>
                    </div>
                  @endforeach
                  </div>

                </div>
              </div>
            </div>

            @else
              <div class="row mt-5">
                <div class="col-md-6">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="name" required="" value="{{old('name')}}">
                    <label for="name">Họ và Tên *</label>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="phone" required="" value="{{old('phone')}}">
                    <label for="phone">Số Điện Thoại *</label>
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="zip" required="" value="{{old('zip')}}">
                    <label for="zip">Mã Bưu Điện *</label>
                    @error('zip') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating mt-3 mb-3">
                    <input type="text" class="form-control" name="state" required="" value="{{old('state')}}">
                    <label for="state">Xã *</label>
                    @error('state') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="city" required="" value="{{old('city')}}">
                    <label for="city">Tỉnh / Thành phố *</label>
                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="address" required="" value="{{old('address')}}">
                    <label for="address">Số Nhà / Tên Tòa Nhà *</label>
                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="locality" required="" value="{{old('locality')}}">
                    <label for="locality">Tên Đường / Khu Vực  *</label>
                    @error('locality') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-floating my-3">
                    <input type="text" class="form-control" name="landmark" required="" value="{{old('landmark')}}">
                    <label for="landmark">Ghi chú *</label>
                    @error('landmark') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Đơn hàng của bạn</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>SẢN PHẨM</th>
                      <th class="text-right">TẠM TÍNH</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach (Cart::instance('cart')->content() as $item)
                      <tr>
                        <td>
                          {{ $item->name }} x {{ $item->qty }}
                            <br>
                            <small class="text-muted d-block">
                                Kích Thước: {{ $item->options->size ?? '-' }}
                            </small>
                        
                            <small class="text-muted d-block">
                                Màu: {{ $item->options->color ?? '-' }}
                            </small>

                        </td>
                        <td class="text-right">
                          {{ number_format($item->price * $item->qty, 0, ',', '.') }} đ
                        </td>
                      </tr>
                    @endforeach

                  </tbody>
                </table>
                @if(Session::has('discounts'))
                  <table class="checkout-totals">
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
                        <th>Tiền Sau Giảm</th>
                        <td class="text-right">
                            {{ number_format(Session::get('discounts')['subtotal'] ?? 0, 0, ',', '.') }} đ
                        </td>
                    </tr>

                    <tr>
                        <th>Vận Chuyển</th>
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
                  <table class="checkout-totals">
                    <tbody>
                      <tr>
                      <tr>
                        <th>TẠM TÍNH</th>
                        <td class="text-right">
                            {{ number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0, ',', '.') }} đ
                        </td>
                    </tr>

                    <tr>
                        <th>VẬN CHUYỂN</th>
                        <td class="text-right">Miễn Phí</td>
                    </tr>

                    <tr>
                        <th>THUẾ</th>
                        <td class="text-right">
                            {{ number_format((float) str_replace(',', '', Cart::instance('cart')->tax()), 0, ',', '.') }} đ
                        </td>
                    </tr>

                    <tr>
                        <th>TỔNG CỘNG</th>
                        <td class="text-right">
                            {{ number_format((float) str_replace(',', '', Cart::instance('cart')->total()), 0, ',', '.') }} đ
                        </td>
                    </tr>
                    </tbody>
                  </table>
                @endif
              </div>
              {{-- thanh toán --}}

              {{-- <div class="checkout_payment-methods">
                <div class="form-check">
                  <form action={{ url('/vnpay_payment') }} method='POST'>
                    @csrf
                    <button type="submit" name="redirect" class="primary-btn checkout-btn" style="">Thanh toán
                      VNPay</button>
                  </form>
                </div>
              </div> --}}

              <div class="checkout__payment-methods">
                <h5 class="mb-3">PHƯƠNG THỨC THANH TOÁN</h5>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode1" value="card">
                  <label class="form-check-label" for="mode1">
                    Thẻ Tín Dụng / Ghi Nợ (Visa / Mastercard)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode2" value="vnpay">
                  <label class="form-check-label" for="mode2">
                  Ví Điện Tử / VNPay
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode3" value="cod">
                  <label class="form-check-label" for="mode3">
                   Thanh Toán Khi Nhận Hàng
                  </label>
                </div>
                @error('mode')
                  <div class="text-danger mt-2">
                      {{ $message }}
                  </div>
              @enderror



                <div class="policy-text">
                  Thông tin cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng, hỗ trợ trải nghiệm của bạn trên website này, 
                  và cho các mục đích khác được mô tả trong  <a href="terms.html" target="_blank">Chính sách bảo mật</a>.
                </div>



              </div>
              <input type="hidden" name="redirect" value="1">
              <button class="btn btn-primary btn-checkout">ĐẶT HÀNG</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>
  <script>
document.addEventListener('DOMContentLoaded', function () {

    const addressInput = document.getElementById('address_id_input');
    const detailBox = document.querySelector('.my-account__address-item__detail');

    document.querySelectorAll('.select-address').forEach(el => {
        el.addEventListener('change', function () {

            addressInput.value = this.value;

            // preview nhưng KHÔNG đóng modal
            detailBox.innerHTML = `
                <p><strong>Họ tên:</strong> ${this.dataset.name}</p>
                <p><strong>Địa chỉ:</strong> ${this.dataset.address}</p>
                <p><strong>Khu vực:</strong> ${this.dataset.locality}</p>
                <p><strong>Thành phố:</strong> ${this.dataset.city}</p>
                <p><strong>Ghi chú:</strong> ${this.dataset.landmark}</p>
                <p><strong>Mã Bưu điện (ZIP):</strong> ${this.dataset.zip}</p>
                <p><strong>Số điện thoại:</strong> ${this.dataset.phone}</p>
            `;
        });
    });

});
</script>

@endsection