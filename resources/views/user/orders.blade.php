@extends('layouts.app')
@section('content')
<style>
    .table> :not(caption)>tr>th {
      padding: 0.625rem 1.5rem .625rem !important;
      background-color: #6a6e51 !important;
    }

    .table>tr>td {
      padding: 0.625rem 1.5rem .625rem !important;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
      border-width: 1px 1px;
      border-color: #6a6e51;
    }

    .table> :not(caption)>tr>td {
      padding: .8rem 1rem !important;
    }
    .bg-success {
      background-color: #40c710 !important;
    }

    .bg-danger {
      background-color: #f44032 !important;
    }

    .bg-warning {
      background-color: #f5d700 !important;
      color: #000;
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
    <main class="pt-90" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
          <div class="cart-header">
     <h2 class="page-title">Đơn hàng</h2>

    <a href="{{route('user.index')}}" class="btn-back">
        Quay lại
    </a>
</div>
        <div class="row">
            <div class="col-lg-2">
                 @include('user.account-nav')
            </div>
            <div class="col-lg-10">
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                  <th style="width: 80px">STT</th>
                                    <th>Họ tên</th>
                                    <th class="text-center">Số điện thoại</th>
                                    <th class="text-center">Tạm tính</th>
                                    <th class="text-center">Thuế</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Ngày đặt hàng</th>
                                    {{-- <th class="text-center">Số lượng</th> --}}
                                    <th class="text-center">Ngày giao hàng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                 <td class="text-center">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                 <td class="text-center">{{ $order->name }}</td>
                                 <td class="text-center">{{ $order->phone }}</td>
                                 <td class="text-center">{{ number_format($order->subtotal ?? 0, 0, ',', '.') }} đ</td>
                                <td class="text-center">{{ number_format($order->tax ?? 0, 0, ',', '.') }} đ</td>
                                <td class="text-center">{{ number_format($order->total ?? 0, 0, ',', '.') }} đ</td>
                                 <td class="text-center">
                                    @if($order->status == 'delivered')
                                        <span class="badge bg-success">Đã giao hàng</span>
                                    @elseif($order->status == 'canceled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Đã đặt hàng</span>
                                    @endif
                                 </td>
                                 <td class="text-center">{{ $order->created_at }}</td>
                                 {{-- <td class="text-center">{{ $order->orderItems->count() }}</td> --}}
                                 <td class="text-center">{{ $order->delivered_date }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="fa fa-eye"></i>
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
    </section>
</main>
@endsection