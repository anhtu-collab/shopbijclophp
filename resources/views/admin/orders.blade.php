@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>TẤT CẢ ĐƠN HÀNG</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Bảng Điều Khiển</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Tất Cả Đơn Hàng</div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <form class="form-search">
                                                <fieldset class="name">
                                                    <input type="text" placeholder="Tìm Kiếm..." class="" name="name"
                                                        tabindex="2" value="" aria-required="true" required="">
                                                </fieldset>
                                                <div class="button-submit">
                                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="wg-table table-all-user">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width:70px">STT</th>
                                                        <!-- <th class="text-center">Tên Người Đặt</th> -->
                                                        <th class="text-center">Tên Người Nhận</th>
                                                        <th class="text-center">Số Điện Thoại Nhận Hàng</th>
                                                        <th class="text-center">Tạm Tính</th>
                                                        <th class="text-center">Thuế</th>
                                                        <th class="text-center">Tổng Tiền</th>

                                                        <th class="text-center">Trạng Thái</th>
                                                        <th class="text-center">Ngày Đặt</th>
                                                        <!-- {{-- <th class="text-center">Số Lượng</th> --}} -->
                                                        <th class="text-center">Ngày Giao/ Hủy</th>
                                                        <th class="text-center">Hoạt Động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($orders as $order)
                                                    <tr>
                                                        <td class="text-center">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                                        <!-- <td class="text-center">{{$order->name}}</td> -->
                                                        <td class="text-center">{{$order->name}}</td>
                                                        <td class="text-center">{{$order->phone}}</td>
                                                        <td class="text-center">{{ number_format($order->subtotal ?? 0, 0, ',', '.') }} đ</td>
                                                        <td class="text-center">{{ number_format($order->tax ?? 0, 0, ',', '.') }} đ</td>
                                                        <td class="text-center">{{ number_format($order->total ?? 0, 0, ',', '.') }} đ</td>
                                                        <td class="text-center">
                                                            @if($order->status == 'delivered')
                                                         <span class="badge bg-success">Đã giao hàng</span>
                                                     @elseif($order->status == 'canceled')
                                                         <span class="badge bg-danger">Đã hủy</span>
                                                     @else
                                                         <span class="badge bg-warning">Đang xử lý</span>
                                                     @endif
                                                        </td>
                                                        <td class="text-center"> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                                       {{-- <td class="text-center">{{ $order->orderItems->count() }}</td> --}}
                                                       <td class="text-center">
                                                           @if($order->status == 'delivered')
                                                               {{ $order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('d/m/Y H:i') : '-' }}
                                                           @elseif($order->status == 'canceled')
                                                               {{ $order->canceled_date ? \Carbon\Carbon::parse($order->canceled_date)->format('d/m/Y H:i') : '-' }}
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
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                        {{ $orders->links('pagination::bootstrap-5') }}

                                    </div>
                                </div>
                            </div>
                        </div>
@endsection

