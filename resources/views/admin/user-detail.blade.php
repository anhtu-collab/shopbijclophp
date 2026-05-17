@extends('layouts.admin')

@section('content')
<style>
    .badge-ordered {
    background-color: #ffc107; /* vàng */
    color: #fff;
}

.badge-delivered {
    background-color: #28a745; /* xanh lá */
    color: #fff;
}

.badge-canceled {
    background-color: #dc3545; /* đỏ */
    color: #fff;
}

/* làm badge đẹp hơn */
.badge {
    padding: 6px 10px;
    font-weight: 600;
    border-radius: 8px;
}
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi Tiết Người Dùng</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Bảng Điều Khiển</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{route('admin.users')}}">
                            <div class="text-tiny">Tất Cả Người Dùng</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết người dùng</div>
                    </li>
                </ul>
            </div>

        <!-- USER INFO -->
        <div class="card shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="fw-bold fs-3 mb-2 text-dark" >{{ $user->name }}</p>
                    <p class="mb-1"> Email: {{ $user->email }}</p>
                    <p class="mb-1"> SĐT: {{ $user->mobile ?? 'Chưa cập nhật' }}</p>
                </div>

                <span class="badge bg-success fs-6 px-3 py-2">
                    {{ $customerType ?? 'Khách hàng mới' }}
                </span>
            </div>
        </div>

        <!-- STATS -->
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm">
                    <h6 class="text-muted">Tổng chi tiêu</h6>
                    <h4 class="fw-bold text-primary">{{ number_format($totalSpent ?? 0) }}đ</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm">
                    <h6 class="text-muted">Số đơn hàng</h6>
                    <h4 class="fw-bold">{{ $totalOrders ?? 0 }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm">
                    <h6 class="text-muted">Sản phẩm đã mua</h6>
                    <h4 class="fw-bold">{{ $totalProducts ?? 0 }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm">
                    <h6 class="text-muted">Giá trị đơn trung bình</h6>
                    <h4 class="fw-bold text-warning">{{ number_format($avgOrderValue ?? 0) }}đ</h4>
                </div>
            </div>

        </div>

        <!-- DEFAULT ADDRESS -->
        <!-- DEFAULT ADDRESS -->
<div class="row g-4">

    <!-- LEFT: DEFAULT ADDRESS -->
    <div class="col-md-6">

        <div class="card border-0 shadow-sm p-5 h-100" style="border-radius:18px;">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h3 class="mb-3">
                     Địa chỉ mặc định
                </h3>

                @if(!empty($defaultAddress))
                    <span class="badge bg-success px-3 py-2 fs-6">
                        Mặc định
                    </span>
                @endif

            </div>

            @if(!empty($defaultAddress))

                <div class="p-4 rounded-4 border bg-light h-100">

                    <div class="fw-bold fs-3 mb-3 text-dark">
                {{ $defaultAddress->name }}
                </div>

                <div class="mb-2 fs-4">
                    <span class="text-muted">SĐT:</span>
                    <span class="fw-semibold text-dark">{{ $defaultAddress->phone }}</span>
                </div>

                <div class="mb-2 fs-4">
                    <span class="text-muted">Địa chỉ:</span>
                    <span class="text-dark">{{ $defaultAddress->address }}</span>
                </div>

                <div class="mb-2 fs-4">
                    <span class="text-muted">Khu vực:</span>
                    <span class="text-dark">
                        {{ $defaultAddress->locality }}, {{ $defaultAddress->city }}
                    </span>
                </div>

                <div class="mb-2 fs-4">
                    <span class="text-muted">ZIP:</span>
                    <span class="text-dark">{{ $defaultAddress->zip ?? '---' }}</span>
                </div>

                <div class="mb-2 fs-4">
                    <span class="text-muted">Ghi chú:</span>
                    <span class="text-dark">{{ $defaultAddress->landmark ?? 'Không có ghi chú' }}</span>
                </div>

                </div>

            @else
                <div class="text-muted fs-6">
                    Chưa có địa chỉ mặc định
                </div>
            @endif

        </div>

    </div>


    <!-- RIGHT: OTHER ADDRESSES -->
    <div class="col-md-6">

        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius:18px;">

            <h3 class="mb-3">
                 Địa chỉ khác
            </h3>

            @if(!empty($otherAddresses) && count($otherAddresses) > 0)

                <div style="max-height:500px; overflow-y:auto;">

                    @foreach($otherAddresses as $addr)

                        <div class="p-3 mb-3 border rounded-4 d-flex justify-content-between"
                             style="background:#fafafa;">

                            <div class="p-4 rounded-4 border bg-light h-100">

                                <div class="fw-bold fs-3 mb-3 text-dark">
                                    {{ $addr->name }}
                                </div>

                                <div class="mb-2 fs-4">
                                    <span class="text-muted">SĐT:</span>
                                    <span class="fw-semibold text-dark">{{ $addr->phone }}</span>
                                </div>

                                <div class="mb-2 fs-4">
                                    <span class="text-muted">Địa chỉ:</span>
                                    <span class="text-dark">{{ $addr->address }}</span>
                                </div>

                                <div class="mb-2 fs-4">
                                    <span class="text-muted">Khu vực:</span>
                                    <span class="text-dark">
                                        {{ $addr->locality }}, {{ $addr->city }}
                                    </span>
                                </div>

                                <div class="mb-2 fs-4">
                                    <span class="text-muted">ZIP:</span>
                                    <span class="text-dark">{{ $addr->zip ?? '---' }}</span>
                                </div>

                                <div class="mb-2 fs-4">
                                    <span class="text-muted">Ghi chú:</span>
                                    <span class="text-dark">{{ $addr->landmark ?? 'Không có ghi chú' }}</span>
                                </div>

                            </div>

                            <div>
                                @if($addr->is_default)
                                    <span class="badge bg-success">Mặc Định</span>
                                @else
                                    <span class="badge bg-secondary">Địa chỉ Thêm</span>
                                @endif
                            </div>

                        </div>

                    @endforeach

                </div>

            @else
                <p class="text-muted">Không có địa chỉ khác</p>
            @endif

        </div>

    </div>

</div>

        <!-- ORDER HISTORY -->
        <div class="card mt-4 p-4 shadow-sm">

    <h5 class="mb-3">Lịch Sử Mua Hàng</h5>

    <div class="table-responsive">

        <table class="table table-hover table-bordered align-middle">

            <thead class="table-light fs-3 ">
                <tr>
                    <th>Mã đơn</th>
                    <th>Tên sản phẩm</th>
                    <th >Tổng tiền</th>
                    <th>Số lượng</th>
                    <th>Mã sản phẩm</th>
                    <th>Thương hiệu</th>
                    <th>Tùy chọn</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                </tr>
            </thead>

            <tbody>

                @if(!empty($orders) && count($orders) > 0)

                   @foreach($orders as $order)
                        <tr>
                            <td class="fs-4">#{{ $order->id }}</td>

                            {{-- TÊN SP --}}
                            <td class="fs-4">
                                @foreach($order->items as $item)
                                    {{ $item->product->name ?? '---' }} <br>
                                @endforeach
                            </td>

                            {{-- TỔNG TIỀN --}}
                            <td class="fw-bold text-danger fs-4">{{ number_format($order->total ?? 0, 0, ',', '.') }} đ</td>

                            {{-- SỐ LƯỢNG --}}
                            <td class="fs-4">
                                {{ $order->items->sum('quantity') }}
                            </td>

                            {{-- MÃ SP --}}
                            <td class="fs-4">
                                @foreach($order->items as $item)
                                    {{ $item->product->SKU ?? '---' }} <br>
                                @endforeach
                            </td>

                            {{-- THƯƠNG HIỆU --}}
                            <td class="fs-4">
                                @foreach($order->items as $item)
                                    {{ $item->product->brand->name ?? '---' }} <br>
                                @endforeach
                            </td >

                            {{-- TÙY CHỌN --}}
                            <td class="fs-4">
                                @foreach($order->items as $item)
                                    @php $opt = $item->options; @endphp

                                    @if(!empty($opt['size']))
                                        Size: {{ $opt['size'] }}
                                    @endif

                                    @if(!empty($opt['color']))
                                        | Màu: {{ $opt['color'] }}
                                    @endif

                                    <br>
                                @endforeach
                            </td>

                            {{-- TRẠNG THÁI --}}
                            @php
                                $statusMap = [
                                    'ordered' => 'Đang xử lý',
                                    'delivered' => 'Đã giao',
                                    'canceled' => 'Đã hủy',
                                ];

                                $statusClass = [
                                    'ordered' => 'badge-ordered',
                                    'delivered' => 'badge-delivered',
                                    'canceled' => 'badge-canceled',
                                ];
                            @endphp

                            <td class="fs-4">
                                <span class="badge {{ $statusClass[$order->status] ?? 'bg-secondary' }}">
                                    {{ $statusMap[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            {{-- NGÀY --}}
                            <td class="fs-4" >
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach

                        @else

                      <tr class="fs-4">
                        <td colspan="9" class="text-center text-muted py-4">
                            Chưa có đơn hàng nào
                        </td>
                    </tr>

                @endif

            </tbody>

        </table>

    </div>
<div class="d-flex justify-content-start align-items-center gap-4 mt-3">

 <div class="text-muted fs-4">
    Hiển thị {{ $orders->firstItem() }}
    đến {{ $orders->lastItem() }}
    trong {{ $orders->total() }} kết quả
</div>

    <div>
        {{ $orders->links() }}
    </div>

</div>
</div>

        <!-- INSIGHT -->
        {{-- <div class="card mt-4 p-4 shadow-sm">
            <h5 class="mb-3">Phân tích khách hàng</h5>

            <p class="mb-2">
                Lần mua gần nhất:
                <strong>
                    {{ $lastOrderDays ?? 'Chưa có đơn hàng' }} ngày trước
                </strong>
            </p>

            @if(!empty($lastOrderDays))
                @if($lastOrderDays > 60)
                    <span class="badge bg-danger">Nguy cơ rời bỏ</span>
                @elseif($lastOrderDays > 30)
                    <span class="badge bg-warning text-dark">Giảm tương tác</span>
                @else
                    <span class="badge bg-success"> Khách hàng hoạt động tốt</span>
                @endif
            @endif
        </div> --}}

    </div>

</div>
@endsection