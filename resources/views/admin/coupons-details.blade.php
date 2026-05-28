@extends('layouts.admin')

@section('content')

<style>
    .container-box{
        padding: 24px;
        background: #f4f6f9;
        min-height: 100vh;
        font-family: Arial, sans-serif;
    }

    .card{
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    }

    .title{
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 16px;
        color: #222;
    }

    .grid{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .item{
        padding: 12px 14px;
        background: #fafafa;
        border-radius: 10px;
        border: 1px solid #eee;
        font-size: 14px;
    }

    .badge{
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .active{ background: #e8f7ee; color: #1a7f37; }
    .inactive{ background: #fdeaea; color: #c62828; }
    .pending{ background: #fff4e5; color: #b26a00; }

    table{
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    table th{
        text-align: left;
        padding: 12px;
        background: #f7f7f7;
        font-weight: 600;
        border-bottom: 1px solid #eee;
    }

    table td{
        padding: 12px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    table tr:hover{
        background: #fafafa;
    }

    .pagination{
        margin-top: 12px;
    }
</style>
                                <div class="main-content-inner">
                            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3> CHI TIẾT GIẢM GIÁ</h3>
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
                                            <div class="text-tiny">Chi Tiết Mã Giảm Giá</div>
                                        </li>
                                    </ul>
                                </div>

                            <div class="container-box">

    {{-- COUPON INFO --}}
    <div class="card">
        <div class="title">Thông tin phiếu giảm giá</div>

        <div class="grid">
            <div class="item"><b>Mã:</b> {{ $coupon->code }}</div>

            <div class="item">
                <b>Loại:</b>
                {{ $coupon->type == 'percent' ? 'Giảm %' : 'Giảm tiền' }}
            </div>

            <div class="item"><b>Giá trị:</b> {{ number_format($coupon->value, 0, ',', '.') }} đ</div>

            <div class="item"><b>Đơn tối thiểu:</b> {{ number_format($coupon->cart_value ?? 0, 0, ',', '.') }} đ</div>

            <div class="item"><b>Bắt đầu:</b> {{ $coupon->start_date ?? '---' }}</div>

            <div class="item"><b>Kết thúc:</b> {{ $coupon->expiry_date ?? '---' }}</div>

            <div class="item">
                <b>Trạng thái:</b>

                @php
                    $start = !empty($coupon->start_date) ? \Carbon\Carbon::parse($coupon->start_date) : null;
                    $end = !empty($coupon->expiry_date) ? \Carbon\Carbon::parse($coupon->expiry_date) : null;
                    $now = now();
                @endphp

                @if(!$start || !$end)
                    <span class="badge inactive">Thiếu dữ liệu</span>
                @elseif($now->lt($start))
                    <span class="badge pending">Chưa bắt đầu</span>
                @elseif($now->gt($end))
                    <span class="badge inactive">Hết hạn</span>
                @else
                    <span class="badge active">Đang hoạt động</span>
                @endif
            </div>
        </div>
    </div>

    {{-- STATISTICS --}}
    <div class="card">
        <div class="title">Thống kê tổng quan</div>

        <div class="grid">
            <div class="item">
                <b>Số đơn đã dùng:</b> {{ $totalOrders ?? 0 }}
            </div>

            <div class="item">
                <b>Tổng tiền giảm:</b> {{ number_format($totalDiscount ?? 0, 0, ',', '.') }} đ
            </div>

            <div class="item">
                <b>Đơn gần nhất:</b>
                #{{ $orders->first()->id ?? 'Không có' }}
            </div>
        </div>
    </div>

    {{-- ORDERS --}}
    <div class="card">
        <div class="title">Danh sách đơn hàng</div>

        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Ngày</th>
                    <th>Số tiền giảm</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ number_format($order->discount ?? 0, 0, ',', '.') }} đ</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- TOP USERS --}}
    <div class="card">
        <div class="title">Top khách hàng</div>

        <table>
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Số lần dùng</th>
                    <th>Tổng giảm</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $topUsers = $orders->groupBy('user_id')->map(function($items){
                        return [
                            'count' => $items->count(),
                            'total' => $items->sum('discount'),
                            'user' => $items->first()->user
                        ];
                    })->sortByDesc('count');
                @endphp

                @foreach($topUsers as $data)
                    <tr>
                        <td>{{ $data['user']->name ?? 'N/A' }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ number_format($data['total'] ?? 0, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
</div>

@endsection