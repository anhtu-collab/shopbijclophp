@extends('layouts.admin')
@section('content')
   <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>THÊM MÃ GIẢM GIÁ</h3>
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
                                                <div class="text-tiny"> Tất Cả Mã Giảm Giá</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Thêm Mã Giảm Giá</div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.coupon.store') }}">
                                        @csrf
                                        <fieldset class="name">
                                            <div class="body-title">Mã Giảm Giá<span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập tên mã giảm giá" name="code" tabindex="0" value="{{ old('code') }}"aria-required="true" required="">
                                        </fieldset>
                                        @error('code')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="category">
                                            <div class="body-title">Hình Thức Ưu Đãi</div>
                                            <div class="select flex-grow">
                                                <select class="" name="type">
                                                    <option value="">--Chọn--</option>
                                                    <option value="fixed"> Giảm theo số tiền (VNĐ)</option>
                                                    <option value="percent"> Giảm theo phần trăm (%)</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                        @error('type')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Mức Giảm Giá <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập số tiền hoặc phần trăm(%)" name="value" tabindex="0" value="{{ old('code') }}"aria-required="true" required="">
                                        </fieldset>
                                        @error('value')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Áp Dụng Cho Đơn Hàng<span class="tf-color-1">*</span></div>
                                           <input class="flex-grow" type="text" id="cart_value" name="cart_value" placeholder="Nhập giá trị đơn hàng tối thiểu để áp dụng mã" value="{{ old('cart_value') }}"  required>
                                        </fieldset>
                                        @error('cart_value')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Ngày Hết Hạn<span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="date" placeholder="Expiry Date" name="expiry_date" tabindex="0" value="{{old('expiry_date')}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('expiry_date')<span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                   <script>
document.getElementById('cart_value').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
});
</script>

@endsection
