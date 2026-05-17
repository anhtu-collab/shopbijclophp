@extends('layouts.admin')
@section('content')
<style>
.wg-table table {
    table-layout: fixed;  
    width: 100%;
}

.wg-table th,
.wg-table td {
    text-align: center;
    vertical-align: middle;
    padding: 8px 10px;
}


.wg-table th:nth-child(2),
.wg-table td:nth-child(2) {
    max-width: 150px;           
    white-space: nowrap;         
    overflow: hidden;           
    text-overflow: ellipsis;     
}

.wg-table td:nth-child(2) span {
    cursor: default;
}

@media (max-width: 768px) {
    .wg-table table {
        font-size: 14px;
    }

    .wg-table th:nth-child(2),
    .wg-table td:nth-child(2) {
        max-width: 100px;
    }
}

    </style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>KIỂM TRA ĐƠN HÀNG</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Bảng Điều Khiển</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Tất Cả Đơn Hàng</div></li>
            </ul>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Tìm Kiếm..." name="search"
                                value="{{ request('search') }}" required>
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                               <tr>
                                <th>STT</th>
                                <th>Tên Người Nhận</th>
                                <th>Số Điện Thoại</th>
                                <th>Tạm Tính</th>
                                <th>Thuế</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
                                {{-- <th>Số lượng</th> --}}
                                {{-- <th>Ngày Giao / Hủy</th> --}}
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="text-center">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                <td class="text-center">{{ $order->name ?? $order->customer_name }}</td>
                                <td class="text-center">{{ $order->phone ?? '-' }}</td>
                                <td class="text-center">{{ number_format($order->subtotal ?? 0, 0, ',', '.') }} đ</td>
                                <td class="text-center">{{ number_format($order->tax ?? 0, 0, ',', '.') }} đ</td>
                                <td class="text-center">{{ number_format($order->total ?? 0, 0, ',', '.') }} đ</td>
                                <td class="text-center">
                                   @if($order->status == 'delivered')
                                        <span class="badge bg-success">Đã Giao</span>
                                    @elseif($order->status == 'canceled')
                                        <span class="badge bg-danger">Đã Hủy</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Đang Xử Lý</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                {{-- <td class="text-center">{{ $order->orderItems->count() }}</td> --}}
                                {{-- <td class="text-center">
                                    @if($order->status == 'delivered')
                                        {{ $order->delivered_date ? $order->delivered_date->format('d/m/Y H:i') : '-' }}
                                    @elseif($order->status == 'canceled')
                                        {{ $order->canceled_date ? $order->canceled_date->format('d/m/Y H:i') : '-' }}
                                    @else
                                        -
                                    @endif
                                </td> --}}
                                <td class="text-center">
                                    @if($order->status == 'ordered')
                                    <form action="{{ route('admin.order.status.update') }}" method="POST" class="d-flex gap-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <select name="order_status" class="form-select form-select-sm">
                                            <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Đang Xử Lý</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã Giao</option>
                                            <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã Hủy</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Cập Nhật</button>
                                    </form>
                                    @else
                                        <em>Không Có Thao Tác</em>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">Không có đơn hàng nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection