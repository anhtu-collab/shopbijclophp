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

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(request('highlight'))
        @endif

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.order.tracking') }}">
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
                                <th>STT</th>
                                <th>Tên Người Nhận</th>
                                <th>Số Điện Thoại</th>
                                <th>Tạm Tính</th>
                                <th>Thuế</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
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
                                   @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-secondary">Chờ Xác Nhận</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-info">Đã Xác Nhận</span>
                                            @break
                                        @case('processing')
                                            <span class="badge bg-primary">Đang Chuẩn Bị Hàng</span>
                                            @break
                                        @case('shipping')
                                            <span class="badge bg-warning">Đang Giao Hàng</span>
                                            @break
                                        @case('delivered')
                                            <span class="badge bg-success">Đã Giao</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success text-dark">Hoàn Tất</span>
                                            @break
                                        @case('canceled')
                                            <span class="badge bg-danger">Đã Hủy</span>
                                            @break
                                        @case('returned')
                                            <span class="badge bg-warning text-dark">Trả Hàng</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Chờ Xác Nhận</span>
                                    @endswitch
                                </td>
                                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    @if(in_array($order->status, ['pending', 'confirmed', 'processing', 'shipping']))
                                        <form action="{{ route('admin.order.status.update') }}" method="POST" class="d-flex gap-1 auto-confirm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <select name="order_status" class="form-select form-select-sm">
                                                @if($order->status == 'pending')
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ Xác Nhận</option>
                                                    <option value="confirmed">Xác Nhận</option>
                                                @elseif($order->status == 'confirmed')
                                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã Xác Nhận</option>
                                                    <option value="processing">Chuẩn Bị Hàng</option>
                                                @elseif($order->status == 'processing')
                                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang Chuẩn Bị Hàng</option>
                                                    <option value="shipping">Giao Hàng</option>
                                                @elseif($order->status == 'shipping')
                                                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang Giao Hàng</option>
                                                    <option value="delivered">Đã Giao</option>
                                                @endif
                                                <option value="canceled">Hủy Đơn</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Cập Nhật</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Đơn đã hoàn tất</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có đơn hàng nào</td>
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
    <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const highlightId = {{ request('highlight') ? request('highlight') : 'null' }};
                    const rows = document.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const orderIdCell = row.querySelector('td:nth-child(1)');
                        if (highlightId !== null && orderIdCell && orderIdCell.textContent.trim() === highlightId.toString()) {
                            row.style.backgroundColor = '#fff3cd';
                            row.scrollIntoView({ behavior: 'smooth', block: 'center' });

                            // Xóa highlight sau 3 giây
                            setTimeout(() => {
                                row.style.backgroundColor = '';
                            }, 3000);
                        }
                    });

                    const autoConfirmForm = document.querySelector('form.auto-confirm');
                    if (autoConfirmForm) {
                        setTimeout(() => {
                            const select = autoConfirmForm.querySelector('select[name="order_status"]');
                            if (select) {
                                select.value = 'confirmed';
                                autoConfirmForm.submit();
                            }
                        }, 3000);
                    }
                });
            </script>
@endsection