@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>TẤT CẢ MÃ GIẢM GIÁ</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Bảng Điều Khiển </div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Tất Cả Mã Giảm Giá</div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <form class="form-search" method="GET" action="{{ route('admin.coupons') }}">
                                                <fieldset class="name">
                                                    <input type="text" placeholder="Tìm Kiếm..." class="" name="search"
                                                        tabindex="2" value="{{ request('search') }}" aria-required="true" required="">
                                                </fieldset>
                                                <div class="button-submit">
                                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                        <a class="tf-button style-1 w208" href="{{ route('admin.coupon.add') }}"><i class="icon-plus"></i>Thêm</a>
                                    </div>
                                    <div class="wg-table table-all-user">
                                        <div class="table-responsive">
                                            @if(Session::has('status')) 
                                            <p class="alert alert-success">{{Session::get('status')}}</p>
                                            @endif    
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">STT</th>
                                                        <th class="text-center">Mã Giảm Giá</th>
                                                        <th class="text-center">Hình Thức Giảm Giá</th>
                                                        <th class="text-center">Mức Giảm Giá</th>
                                                        <th class="text-center">Áp Dụng Cho Đơn Hàng</th>
                                                        <th class="text-center">Ngày Hết Hạn</th>
                                                        <th class="text-center">Trạng thái</th>
                                                        <th class="text-center">Hoạt Động</th>
                                                    </tr>
                                                </thead>
                                                @foreach($coupons as $coupon)
                                                
                                                <tbody>
                                                    <tr>
                                                        <td>{{ ($coupons->currentPage() - 1) * $coupons->perPage() + $loop->iteration }}</td>
                                                        <td>{{ $coupon->code }}</td>
                                                        <td>
                                                            @if($coupon->type == 'fixed')
                                                                Giảm theo số tiền (VNĐ)
                                                            @else
                                                                Giảm theo phần trăm (%)
                                                            @endif
                                                        </td> 
                                                      <td>
                                                          @if($coupon->type == 'percent')
                                                              {{ (int)$coupon->value }}%
                                                          @else
                                                              {{ number_format($coupon->value, 0, ',', '.') }} đ
                                                          @endif
                                                      </td>
                                                        
                                                        <td>{{ number_format((float) $coupon->cart_value, 0, ',', '.') }} đ</td>
                                                        <td>{{ $coupon->expiry_date }}</td>
                                                        <td>
                                                            @if($coupon->expiry_date && \Carbon\Carbon::parse($coupon->expiry_date)->isPast())
                                                                <span class="text-danger">Hết hạn</span>
                                                            @else
                                                                <span class="text-success">Còn hạn</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="list-icon-function">
                                                                <a href="{{ route('admin.coupon.details', $coupon->id) }}">
                                                                <div class="list-icon-function view-icon">
                                                                    <div class="item eye">
                                                                        <i class="icon-eye"></i>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                                <a href="{{ route('admin.coupon.edit',['id'=>$coupon->id]) }}">
                                                                    <div class="item edit">
                                                                        <i class="icon-edit-3"></i>
                                                                    </div>
                                                                </a>
            
                                                                <form action="{{ route('admin.coupon.delete', ['id' => $coupon->id]) }}" method="POST">
                                                                    @csrf
                                                                     @method('DELETE')
                                                                    <div class="item text-danger delete">
                                                                        <i class="icon-trash-2"></i>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                        {{ $coupons->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
@endsection
@push('scripts')
<script>
    $(function() {
        $('.delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            swal({
                title: "Bạn Chắc Chắn ?",
                text: "Muốn Xóa Không?",
                type: "Cảnh Báo",
                buttons: ["Không", "Có"],
                confirmButtonColor: '#dc3545'
            }).then(function(result) {
                if (result) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush