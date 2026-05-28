@extends('layouts.admin')
@section('content')
     <div class="main-content-inner">

                            <div class="main-content-wrap">
                                <div class="tf-section-2 mb-30">
                                    <div class="flex gap20 flex-wrap-mobile">
                                        <div class="w-half">

                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-shopping-bag"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Tổng đơn Hàng</div>
                                                            <h4>{{$dashboardDatas[0]->Total}}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-dollar-sign"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Tổng Doanh Thu</div>
                                                            <h4>{{ number_format($dashboardDatas[0]->TotalAmount ?? 0, 0, ',', '.') }} đ</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-shopping-bag"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Đơn Chờ Xử Lý</div>
                                                            <h4>{{$dashboardDatas[0]->TotalOrdered}}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="wg-chart-default">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-dollar-sign"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Giá Trị Đơn Chờ</div>
                                                           <h4> {{ number_format($dashboardDatas[0]->TotalOrderedAmount ?? 0, 0, ',', '.') }} đ</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="w-half">

                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-shopping-bag"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Đơn Đã Giao</div>
                                                            <h4>{{$dashboardDatas[0]->TotalDelivered}}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-dollar-sign"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Doanh Thu Thực Tế</div>
                                                            <h4>{{ number_format($dashboardDatas[0]->TotalDeliveredAmount ?? 0, 0, ',', '.') }} đ</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="wg-chart-default mb-20">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-shopping-bag"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Đơn Đã Hủy</div>
                                                            <h4>{{$dashboardDatas[0]->TotalCanceled}}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="wg-chart-default">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap14">
                                                        <div class="image ic-bg">
                                                            <i class="icon-dollar-sign"></i>
                                                        </div>
                                                        <div>
                                                            <div class="body-text mb-2">Giá Trị Đơn Hủy</div>
                                                            <h4>{{ number_format($dashboardDatas[0]->TotalCanceledAmount ?? 0, 0, ',', '.') }} đ</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="wg-box">
                                        <div class="flex items-center justify-between">
                                            <h5>Doanh Thu Theo Tháng</h5>
                                        </div>
                                        <div class="flex flex-wrap gap40">
                                            <div>
                                                <div class="mb-2">
                                                    <div class="block-legend">
                                                        <div class="dot t1"></div>
                                                        <div class="text-tiny">Tổng Cộng</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap10">
                                                    <h4>{{ number_format($TotalAmount, 0, ',', '.') }}</h4>
                                                   
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-2">
                                                    <div class="block-legend">
                                                        <div class="dot t2"></div>
                                                        <div class="text-tiny">Chờ Xử Lý</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap10">
                                                    <h4>{{ number_format($TotalOrderedAmount ?? 0, 0, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-2">
                                                    <div class="block-legend">
                                                        <div class="dot t2"></div>
                                                        <div class="text-tiny">Đã Giao</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap10">
                                                    <h4>{{ number_format($TotalDeliveredAmount ?? 0, 0, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-2">
                                                    <div class="block-legend">
                                                        <div class="dot t2"></div>
                                                        <div class="text-tiny">Đã Hủy</div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap10">
                                                    <h4>{{ number_format($TotalCanceledAmount ?? 0, 0, ',', '.') }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="line-chart-8"></div>
                                    </div>

                                </div>
                                <div class="tf-section mb-30">

                                    <div class="wg-box">
                                        <div class="flex items-center justify-between">
                                            <h5>Tổng Đơn Hàng</h5>
                                            <div class="dropdown default">
                                                <a class="btn btn-secondary dropdown-toggle" href="{{ route('admin.orders') }}">
                                                    <span class="view-all">Xem Tất Cả</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="wg-table table-all-user">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:70px">STT</th>
                                                        <th class="text-center">Tên Người Nhận</th>
                                                        <th class="text-center">Số Điện Thoại</th>
                                                        <th class="text-center">Tạm Tính</th>
                                                        <th class="text-center">Thuế</th>
                                                        <th class="text-center">Tổng Tiền</th>

                                                        <th class="text-center">Trạng Thái</th>
                                                        <th class="text-center">Ngày Đặt</th>
                                                        {{-- <th class="text-center">Số Lượng</th> --}}
                                                        <th class="text-center">Ngày Giao</th>
                                                        <th class="text-center">Hoạt Động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($orders as $order)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td class="text-center">{{$order->name}}</td>
                                                        <td class="text-center">{{$order->phone}}</td>
                                                        <td class="text-center">{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                                                        <td class="text-center">{{ number_format($order->tax, 0, ',', '.') }} đ</td>
                                                        <td class="text-center">{{ number_format($order->total, 0, ',', '.') }} đ</td>
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
                                                        <td class="text-center">{{$order->created_at}}</td>
                                                        {{-- <td class="text-center">{{$order->orderItems->count()}}</td> --}}
                                                            <td class="text-center">
                                                                @if($order->status === 'delivered')
                                                                    {{ $order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y') : '-' }}
                                                                @elseif($order->status === 'canceled')
                                                                    {{ $order->canceled_date ? \Carbon\Carbon::parse($order->canceled_date)->format('d/m/Y') : '-' }}
                                                                @elseif($order->status === 'returned')
                                                                    {{ $order->returned_date ? \Carbon\Carbon::parse($order->returned_date)->format('d/m/Y') : '-' }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('admin.order.details', ['order_id' => $order->id])}}">
                                                                <div class="list-icon-function view-icon">
                                                                    <div class="item eye">
                                                                        <i class="icon-eye"></i>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
@endsection
@push('scripts')
<script>
        (function ($) {

            var tfLineChart = (function () {

                var chartBar = function () {

                    var options = {
                        series: [{
                            name: 'Tổng doanh thu',
                            data: [{{ $AmountM }}]
                        }, {
                            name: 'Đang chờ',
                            data: [{{ $OrderedAmountM }}]
                        },
                        {
                            name: 'Đã giao',
                            data: [{{ $DeliveredAmountM }}]
                        }, {
                            name: 'Đã hủy',
                            data: [{{ $CanceledAmountM }}]
                        }],
                        chart: {
                            type: 'bar',
                            height: 325,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '10px',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false,
                        },
                        colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                        stroke: {
                            show: false,
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: '#212529',
                                },
                            },
                            categories: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                        },
                        yaxis: {
                            show: false,
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                   return val.toLocaleString('vi-VN') + " VNĐ"
                                }
                            }
                        }
                    };

                    chart = new ApexCharts(
                        document.querySelector("#line-chart-8"),
                        options
                    );
                    if ($("#line-chart-8").length > 0) {
                        chart.render();
                    }
                };
                return {
                    init: function () { },

                    load: function () {
                        chartBar();
                    },
                    resize: function () { },
                };
            })();

            jQuery(document).ready(function () { });

            jQuery(window).on("load", function () {
                tfLineChart.load();
            });

            jQuery(window).on("resize", function () { });
        })(jQuery);
    </script>
@endpush