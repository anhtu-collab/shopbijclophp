@extends('layouts.admin')
@section('content')
<style>
                            .table-transaction>tbody>tr:nth-of-type(odd) {
                                --bs-table-accent-bg: #fff !important;
                            }
                           table {
                               width: 100%;
                               table-layout: fixed;
                           }
                           
                           td, th {
                               vertical-align: middle !important;
                           }
                           
                    
                           .pname {
                               display: flex;
                               align-items: center;
                               justify-content: flex-start;
                               gap: 12px;
                               min-height: 80px;
                           }
                           
                           /* IMAGE */
                           .pname .image img {
                               width: 60px;
                               height: 60px;
                               object-fit: cover;
                               border-radius: 6px;
                           }
                           
                           /* NAME TEXT */
                           .pname .name a {
                               display: block;
                               line-height: 1.2;
                               margin-bottom: 0 !important;
                           }
                   
                           td.text-center {
                               word-break: break-word;
                           }
                           
                
                           .badge {
                               display: inline-block;
                               margin: 2px;
                           }
                    
                        </style>
                        <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Chi Tiết Đơn Hàng</h3>
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
                                            <div class="text-tiny">Chi Tiết Đơn Hàng</div>
                                        </li>
                                    </ul>
                                </div>

                                 <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <h5>Chi Tiết Đơn Hàng</h5>
                                        </div>
                                         <a class="tf-button style-1 w208" href="{{ route('admin.orders')}}">Quay Lại</a>
                                    </div>
                                    <div class="table-responsive">
                                        @if(Session::has('status'))
                                            <p class="alert alert-success">{{ Session::get('status') }}</p>
                                         @endif
                                        <table class="table table-striped table-bordered">
                                             <tr>
                                                 <th>STT</th>
                                                 <td>{{ $order->id }}</td>
                                                 <th>Số Điện Thoại</th>
                                                 <td>{{ $order->phone }}</td>
                                                 <th>Mã Zip</th>
                                                 <td>{{ $order->zip }}</td>
                                             </tr>
                                             
                                             <tr>
                                                 <th>Ngày Đặt</th>
                                                 <td>{{ $order->created_at }}</td>
                                                 <th>Ngày Giao</th>
                                                 <td>{{ $order->delivered_date }}</td>
                                                 <th>Ngày Hủy</th>
                                                 <td>{{ $order->canceled_date }}</td>
                                             </tr>
                                             
                                             <tr>
                                                 <th>Trạng Thái Đơn</th>
                                                 <td colspan="5">
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
                                             </tr>
                                        </table>
                                    </div>                            
                                </div>

                                <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <h5>Sản Phẩm Đã Đặt</h5>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                   <tr>
                                                        <th class="text-center">Tên Sản Phẩm</th>
                                                        <th class="text-center">Giá</th>
                                                        <th class="text-center">Số Lượng</th>
                                                        <th class="text-center">Mã Sản Phẩm</th>
                                                        <th class="text-center">Danh Mục</th>
                                                        <th class="text-center">Thương Hiệu</th>
                                                        <th class="text-center">Tùy Chọn</th>
                                                        <th class="text-center">Trả Hàng</th>
                                                    </tr>
                                            </thead>
                                            <tbody>
                                               @foreach($orderItems as $item)
                                                <tr>
                                                    <td class="pname align-middle">
                                                        <div class="image">
                                                          <img src="{{ asset('uploads/products/thumbnails')}}/{{ $item->product->image}}" alt="{{ $item->product->name }}" class="image">
                                                        </div>
                                                        <div class="name ">
                                                            <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}" target="_blank" class="body-title-3 d-block">{{ $item->product->name }}</a>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-center">{{ $item->product->SKU }}</td>
                                                    <td class="text-center">{{ $item->product->category->name }}</td>
                                                    <td class="text-center">{{ $item->product->brand->name }}</td>
                                                    @php
                                                    $options = is_array($item->options) 
                                                    ? $item->options 
                                                    : json_decode($item->options, true);
                                                    @endphp
                                                    <td class="text-center">
                                                        Size: {{ $options['size'] ?? '-' }} <br>
                                                        Color: {{ $options['color'] ?? '-' }}
                                                    </td>

                                                    <td class="text-center">{{ $item->rstatus == 0 ? "Không" : "Có" }}</td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                        {{ $orderItems->links('pagination::bootstrap-5') }}

                                    </div>
                                </div>

            
                                <div class="wg-box mt-5">
                                        <h5>Thông tin đặt hàng</h5>

                                        <div class="row">
                                           
                                            <div class="col-md-6">
                                                    <h6>Địa Chỉ Mặc Định</h6>

                                                    @php
                                                        $defaultAddress = $addresses->where('is_default', 1)->first();
                                                    @endphp

                                                    @if($defaultAddress)
                                                        <div class="my-account__address-item__detail">
                                                             <p><strong> Họ tên:</strong> {{ $defaultAddress->name }}</p>

                                                                    <p><strong> Địa chỉ:</strong> {{ $defaultAddress->address }}</p>

                                                                    <p><strong>Khu vực:</strong> {{ $defaultAddress->locality }}</p>

                                                                    <p><strong> Thành phố:</strong> {{ $defaultAddress->city }}, </p>
                                                                    <p><strong> Quốc gia:</strong> {{ $defaultAddress->country }}</p>

                                                                    <p><strong> Địa chỉ :</strong> {{ $defaultAddress->landmark }}</p>

                                                                    <p><strong> Mã bưu điện:</strong> {{ $defaultAddress->zip }}</p>

                                                                    <hr>

                                                                    <p><strong> SĐT:</strong> {{ $defaultAddress->phone }}</p>
                                                        </div>
                                                    @else
                                                        <p>Không có địa chỉ mặc định</p>
                                                    @endif
                                                </div>
                                           
                                            <div class="col-md-6">
                                                <h6>Địa Chỉ Nhận Hàng</h6>
                                                <div class="my-account__address-item__detail">
                                                    <div class="order-shipping-info">

                                                        <p><strong> Họ tên:</strong> {{ $order->shipping_name ?? $order->name }}</p>

                                                        <p><strong> Địa chỉ:</strong> {{ $order->shipping_address ?? $order->address }}</p>

                                                        <p><strong> Khu vực:</strong> {{ $order->shipping_locality ?? $order->locality }}</p>

                                                        <p><strong> Thành phố:</strong> {{ $order->shipping_city ?? $order->city }},</p>
                                                        <p><strong> Quốc gia:</strong> {{ $order->shipping_country ?? $order->country }}</p>

                                                        <p><strong> Địa chỉ :</strong> {{ $order->shipping_landmark ?? $order->landmark }}</p>

                                                        <p><strong>Mã bưu điện:</strong> {{ $order->shipping_zip ?? $order->zip }}</p>

                                                        <hr>

                                                        <p><strong> SĐT:</strong> {{ $order->shipping_phone ?? $order->phone }}</p>

                                                </div>
                                            </div>
                                        </div>
                                    </div> 

                                <div class="wg-box mt-5">
                                    <h5>Thanh Toán</h5>
                                    <table class="table table-striped table-bordered table-transaction">
                                        <tbody>
                                           <tr>
                                                <th>Tạm Tính</th>
                                                <td>{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>

                                                <th>Thuế</th>
                                                <td>{{ number_format($order->tax, 0, ',', '.') }} đ</td>

                                                <th>Giảm Giá</th>
                                                <td class="text-success">
                                                    -{{ number_format($order->discount, 0, ',', '.') }} đ
                                                </td>
                                            </tr>
                                           <tr>
                                               <th>Tổng Tiền</th>
                                               <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                                               <th>Phương Thức Thanh Toán</th>
                                               <td>{{$transaction?->mode}}</td>
                                               <th>Trạng Thái</th>
                                               <td>
                                                   @if($transaction?->status == 'approved')
                                                       <span class="badge bg-success">Đã Thanh Toán</span>
                                                   @elseif($transaction?->status == 'declined')
                                                       <span class="badge bg-danger">Từ Chối</span>
                                                   @elseif($transaction?->status == 'refunded')
                                                       <span class="badge bg-secondary">Đã Hoàn Tiền</span>
                                                   @else
                                                       <span class="badge bg-warning">Chờ Xử Lý</span>
                                                   @endif
                                               </td>  
                                           </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="wg-box mt-5">
                                    <h5>Cập Nhật Trạng Thái Đơn Hàng</h5>
                                    <form action="{{ route('admin.order.status.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="select">
                                                    <select name="order_status" id="order_status">
                                                        <option value="pending" {{ $order->status == 'pending' ? "selected" : "" }}>Chờ Xác Nhận</option>
                                                        <option value="confirmed" {{ $order->status == 'confirmed' ? "selected" : "" }}>Đã Xác Nhận</option>
                                                        <option value="processing" {{ $order->status == 'processing' ? "selected" : "" }}>Đang Chuẩn Bị Hàng</option>
                                                        <option value="shipping" {{ $order->status == 'shipping' ? "selected" : "" }}>Đang Giao Hàng</option>
                                                        <option value="delivered" {{ $order->status == 'delivered' ? "selected" : "" }}>Đã Giao</option>
                                                        <option value="completed" {{ $order->status == 'completed' ? "selected" : "" }}>Hoàn Tất</option>
                                                        <option value="canceled" {{ $order->status == 'canceled' ? "selected" : "" }}>Đã Hủy</option>
                                                        <option value="returned" {{ $order->status == 'returned' ? "selected" : "" }}>Trả Hàng</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary tf-button w208">Cập Nhật</button>
                                            </div>
                                            @if($order->status != 'canceled')
                                            <div class="col-md-3">
                                                <a href="{{ route('admin.order.invoice', $order->id) }}"
                                                target="_blank"
                                                class="btn btn-dark tf-button w208">
                                                    In Hóa Đơn
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </form>     
                                </div>
                            </div>
                        </div>

@endsection