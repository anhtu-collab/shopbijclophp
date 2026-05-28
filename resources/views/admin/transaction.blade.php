@extends('layouts.admin')
@section('content')

    <div class="main-content-inner">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Tra Cứu Giao Dịch</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Bảng Điều Khiển</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Tra Cứu Giao Dịch</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">

                <form method="GET" action="{{ route('admin.transaction') }}"
                    class="flex items-center gap10 mb-20 flex-wrap">
                    <input type="text" name="phone" value="{{ $phone }}" placeholder="Nhập số điện thoại khách hàng..."
                        style="width:280px; border:1px solid #ccc; border-radius:4px; padding:7px 12px; font-size:14px;"
                        autofocus>
                    <button type="submit" style="background:#4CAF50; color:white; padding:7px 20px;
                                               border:none; border-radius:4px; cursor:pointer; font-size:14px;">
                        Tra cứu
                    </button>
                    @if($phone)
                        <a href="{{ route('admin.transaction') }}"
                            style="color:#e74c3c; font-size:13px; text-decoration:none;">✕ Xóa</a>
                    @endif
                </form>

                @if($phone && $customer)
                    <div style="margin-bottom:16px; font-size:14px; color:#333;">
                        Thông tin khách hàng:
                        <strong>{{ $customer['name'] }}</strong> —
                        <strong>{{ $customer['phone'] }}</strong>
                    </div>
                @elseif($phone && $orders->isEmpty())
                    <div style="margin-bottom:16px; font-size:14px; color:#e74c3c;">
                        Không tìm thấy giao dịch nào với số điện thoại <strong>{{ $phone }}</strong>.
                    </div>
                @endif

                @if($orders->isNotEmpty())
                    <div class="table-responsive" style="overflow-x:auto;">
                        <table class="table table-bordered" style="font-size:13px; border-collapse:collapse; width:100%;">
                            <thead style="background:#f0f0f0;">
                                <tr>
                                    <th class="text-center" style="width:40px;">STT</th>
                                    <th>Mã hóa đơn</th>
                                    <th>Ngày tạo</th>
                                    <th>Khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Chiết khấu</th>
                                    <th class="text-right">Tiền hàng</th>
                                    <th class="text-center">HT Thanh toán</th>
                                    <th class="text-right">Tổng</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalQty = 0;
                                    $totalDiscount = 0;
                                    $totalAmount = 0;
                                @endphp

                                @foreach($orders as $i => $order)
                                    @php
                                        $totalQty += $order->orderItems->sum('quantity');
                                        $totalDiscount += $order->discount ?? 0;
                                        $totalAmount += $order->total;
                                        $payMode = $order->transaction?->mode ?? '—';
                                    @endphp

                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>

                                        <td style="vertical-align:top;">
                                            <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}"
                                                style="font-weight:600; color:#2980b9;">#{{ $order->id }}</a>
                                        </td>

                                        <td style="vertical-align:top; white-space:wrap; margin-right: 5px;">
                                            {{ $order->created_at->format('d/m/Y H:i:s') }}
                                        </td>

                                        <td>
                                            <div style="font-weight:600;">{{ $order->name }}</div>
                                            <div style="color:#777; font-size:12px;">{{ $order->phone }}</div>
                                        </td>

                                        <td>
                                            @foreach($order->orderItems as $item)
                                                <br style="display:flex; justify-content:space-between; gap:16px; padding:2px 0;vertical-align:top; white-space:wrap;">
                                                    <span>{{ $item->product?->SKU ?? '—' }}:</span> 
                                                    <span style="min-width:20px; text-align:right;">{{ $item->quantity }}</span> 
                                                    <span style="min-width:80px; text-align:right; color:#555;">
                                                        {{ $item->price > 0 ? number_format($item->price, 0, ',', '.') : '00' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </td>

                                        <td class="text-center" style="font-weight:600;">
                                            {{ $order->orderItems->sum('quantity') }}
                                        </td>

                                        <td class="text-center">
                                            {{ $order->discount > 0 ? number_format($order->discount, 0, ',', '.') : '0' }}
                                        </td>

                                        <td class="text-right">
                                            {{ number_format($order->subtotal, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            @if($payMode === 'cod')
                                                <span style="background:#e8f5e9; color:#2e7d32; padding:2px 8px; border-radius:4px; font-size:12px;">COD</span>
                                            @elseif($payMode === 'vnpay')
                                                <span style="background:#e3f2fd; color:#1565c0; padding:2px 8px; border-radius:4px; font-size:12px;">VNPay</span>
                                            @else
                                                <span style="font-size:12px; color:#999;">{{ strtoupper($payMode) }}</span>
                                            @endif
                                        </td>

                                        <td class="text-right" style="font-weight:600;">
                                            {{ number_format($order->total, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            @if($order->status === 'ordered')
                                                <span style="background:#fff3e0; color:#e65100; padding:2px 8px; border-radius:4px; font-size:12px;">Chờ</span>
                                            @elseif($order->status === 'delivered')
                                                <span style="background:#e8f5e9; color:#2e7d32; padding:2px 8px; border-radius:4px; font-size:12px;">Đã giao</span>
                                            @elseif($order->status === 'canceled')
                                                <span style="background:#ffebee; color:#c62828; padding:2px 8px; border-radius:4px; font-size:12px;">Đã hủy</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot style="background:#f8f9fa; font-weight:700;">
                                <tr>
                                    <td colspan="2" class="text-center">
                                        Tổng: {{ $orders->count() }}
                                    </td>
                                    <td colspan="3"></td>
                                    <td class="text-center">{{ $totalQty }}</td>
                                    <td class="text-center">{{ number_format($totalDiscount, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($orders->sum('subtotal'), 0, ',', '.') }}</td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($totalAmount, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection