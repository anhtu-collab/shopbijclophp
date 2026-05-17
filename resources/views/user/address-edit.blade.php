@extends('layouts.app')

@section('content')
<style>
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
      <h2 class="page-title">Sửa địa chỉ</h2>

    <a href="{{ route('user.address') }}" class="btn-back">
        Quay lại
    </a>
</div>

        @if(Session::has('success'))
            <p class="alert alert-success">{{ Session::get('success') }}</p>
        @endif

        @if(Session::has('error'))
            <p class="alert alert-danger">{{ Session::get('error') }}</p>
        @endif

        <form method="POST" action="{{ route('user.address.update', $address->id) }}">
            @csrf

            <input type="hidden" name="id" value="{{ $address->id }}">

            <div class="mb-3">
                <label>Họ tên</label>
                <input type="text" name="name" class="form-control" value="{{ $address->name }}">
            </div>

            <div class="mb-3">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ $address->phone }}">
            </div>

            <div class="mb-3">
                <label>Mã zip</label>
                <input type="text" name="zip" class="form-control" value="{{ $address->zip }}">
            </div>

            <div class="mb-3">
                <label>Tỉnh</label>
                <input type="text" name="state" class="form-control" value="{{ $address->state }}">
            </div>

            <div class="mb-3">
                <label>Thành phố</label>
                <input type="text" name="city" class="form-control" value="{{ $address->city }}">
            </div>

            <div class="mb-3">
                <label>Quốc gia</label>
                <input type="text" name="country" class="form-control" value="{{ $address->country }}">
            </div>

            <div class="mb-3">
                <label>Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{ $address->address }}">
            </div>

            <div class="mb-3">
                <label>Khu vực</label>
                <input type="text" name="locality" class="form-control" value="{{ $address->locality }}">
            </div>

            <div class="mb-3">
                <label>Mốc địa điểm</label>
                <input type="text" name="landmark" class="form-control" value="{{ $address->landmark }}">
            </div>

            <div class="mb-3">
                <label>
                    <input type="checkbox" name="is_default" {{ $address->is_default ? 'checked' : '' }}>
                    Đặt làm mặc định
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
    </section>
</main>
@endsection