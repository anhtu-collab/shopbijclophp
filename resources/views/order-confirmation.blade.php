@extends('layouts.app')
@section('content')
<style>
    .text-success {
        color: #278c04 !important;
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>

    <section class="shop-checkout container">
        <h2 class="page-title">Đơn hàng của bạn</h2>

        <div class="checkout-steps">
            <a href="javascript:void(0)" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Giỏ hàng</span>
                    <em>Quản lý danh sách sản phẩm</em>
                </span>
            </a>

            <a href="javascript:void(0)" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
                    <span>Vận chuyển & Thanh toán</span>
                    <em>Kiểm tra đơn hàng của bạn</em>
                </span>
            </a>

            <a href="javascript:void(0)" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Xác nhận</span>
                    <em>Xem lại và gửi đơn hàng</em>
                </span>
            </a>
        </div>

        <div class="order-complete">
            <div class="order-complete__message">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#B9A16B" />
                    <path
                        d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z"
                        fill="white" />
                </svg>

                <h3>Đơn hàng của bạn đã hoàn tất!</h3>
                <p>Cảm ơn bạn. Đơn hàng của bạn đã được ghi nhận.</p>
            </div>

            <div class="order-info">
                <div class="order-info__item">
                    <label>Ngày</label>
                    <span>{{$order->created_at}}</span>
                </div>

                <div class="order-info__item">
                    <label>Tổng tiền</label>
                    <span>{{ number_format($order->total, 0, ',', '.') }} đ</span>
                </div>

                <div class="order-info__item">
                    <label>Phương thức thanh toán</label>
                    <span>{{$order->transaction?->mode}}</span>
                </div>
            </div>

            <div class="checkout__totals-wrapper">
                <div class="checkout__totals">
                    <h3>Chi tiết đơn hàng</h3>

                    <table class="checkout-cart-items">
                        <thead>
                            <tr>
                                <th>SẢN PHẨM</th>
                                <th>TẠM TÍNH</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    {{$item->product->name}} x {{$item->quantity}}
                                    <br>
                                    <small class="text-muted d-block">
                                        Size: {{ $item->options['size'] ?? '-' }}
                                    </small>

                                    <small class="text-muted d-block">
                                        Màu: {{ $item->options['color'] ?? '-' }}
                                    </small>
                                </td>

                                <td class="text-right">
                                    {{ number_format($item->price, 0, ',', '.') }} đ
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="checkout-totals">
                        <tbody>
                           <tr>
                                <th>Tạm tính</th>
                                <td class="text-right">
                                    {{ number_format($order->subtotal, 0, ',', '.') }} đ
                                </td>
                            </tr>

                            <tr>
                                <th>Giảm giá</th>
                                <td class="text-success text-right">
                                    -{{ number_format($order->discount ?? 0, 0, ',', '.') }} đ
                                </td>
                            </tr>

                            <tr>
                                <th>Vận chuyển</th>
                                <td class="text-right">Miễn phí</td>
                            </tr>

                            <tr>
                                <th>Thuế</th>
                                <td class="text-right">
                                    {{ number_format($order->tax, 0, ',', '.') }} đ
                                </td>
                            </tr>

                            <tr>
                                <th><strong>Tổng cộng</strong></th>
                                <td class="text-right">
                                    <strong>{{ number_format($order->total, 0, ',', '.') }} đ</strong>
                                </td>
                            </tr>


        
                                </tbody>
                    </table>

                </div>
            </div>
                        <div class="d-flex justify-content-end ">
            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary px-4 py-2">
                Tiếp tục mua sắm
            </a>
        </div>

        </div>
    </section>
</main>
@endsection