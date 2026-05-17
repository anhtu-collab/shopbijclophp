@extends('layouts.app')
@section('content')
<style>
    .underline-link {
    text-decoration: underline;
    text-underline-offset: 3px;
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
    <h2 class="page-title">Tài khoản của tôi</h2>

    <a href="{{ route('shop.index') }}" class="btn-back">
        Quay lại
    </a>
</div>

        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>

            <div class="col-lg-9">
                <div class="page-content my-account__dashboard">
                    <p>Xin chào <strong>{{ Auth::user()->name }}</strong></p>

                    <p>
                        Từ bảng điều khiển tài khoản, bạn có thể xem
                        <a class="underline-link" href="{{ route('user.orders') }}">các đơn hàng gần đây</a>,
                        quản lý <a class="underline-link" href="{{ route('user.address') }}">địa chỉ giao hàng</a>,
                        và <a class="underline-link" href="{{ route('user.details') }}">chỉnh sửa mật khẩu và thông tin tài khoản</a>.
                    </p>

                </div>
            </div>
        </div>
    </section>
</main>
@endsection