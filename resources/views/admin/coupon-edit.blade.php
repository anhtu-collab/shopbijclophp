@extends('layouts.admin')
@section('content')
   <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>SỬA MÃ GIẢM GIÁ</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{ route('admin.index') }}">
                                                <div class="text-tiny">Bảng Điều Khiển</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.coupons') }}">
                                                <div class="text-tiny">Tất Cả Mã Giảm Giá</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Sửa Mã Giảm Giá</div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.coupon.update') }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $coupon->id }}" />
                                        <fieldset class="name">
                                            <div class="body-title">Mã Giảm Giá <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập tên mã giảm giá" name="code" tabindex="0" value="{{$coupon->code}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('code')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="category">
                                            <div class="body-title">Hình Thức Ưu Đãi</div>
                                            <div class="select flex-grow">
                                                <select class="" name="type">
                                                    <option value="">--Chọn--</option>
                                                    <option value="fixed"{{ $coupon->type == 'fixed' ? 'selected' : '' }}>Giảm theo số tiền(VNĐ)</option>
                                                    <option value="percent"{{ $coupon->type == 'percent' ? 'selected' : '' }}>Giảm theo phần trăm(%)</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                        @error('type')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Mức Giảm Giá <span class="tf-color-1">*</span></div>
                                           <input class="flex-grow" type="text" id="value" name="value" placeholder="Nhập số tiền hoặc phần trăm(%)" value="{{ old('value', number_format($coupon->value, 0, ',', '.')) }}" required>
                                        </fieldset>
                                        @error('value')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Áp Dụng Cho Đơn Hàng<span class="tf-color-1">*</span></div>
                                           <input class="flex-grow"  type="text" id="cart_value" placeholder="Nhập giá trị đơn hàng để dùng mã" name="cart_value" value="{{ old('cart_value', number_format($coupon->cart_value, 0, ',', '.')) }}" required >
                                        </fieldset>
                                        @error('cart_value')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Ngày Hết Hạn<span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="date" placeholder="Expiry Date" name="expiry_date" tabindex="0" value="{{$coupon->expiry_date}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('expiry_date')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
document.getElementById('value').addEventListener('input', function (e) {
    let val = e.target.value.replace(/\D/g, '');
    e.target.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
});
</script>

@endsection
