@extends('layouts.admin')
@section('content')
  <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>TẤT CẢ SẢN PHẨM</h3>
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
                                            <div class="text-tiny">Tất Cả Sản Phẩm</div>
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
                                        <a class="tf-button style-1 w208" href="{{ route('admin.product.add') }}"><i
                                                class="icon-plus"></i>Thêm</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Thứ Tự</th>
                                                    <th class="text-center">Tên SẢn Phẩm</th>
                                                    <th class="text-center" >Giá Gốc</th>
                                                    <th class="text-center" >Giảm Giá</th>
                                                    <th class="text-center" >Mã</th>
                                                    <th class="text-center" >Danh Mục</th>
                                                    <th class="text-center" >Thương Hiệu</th>
                                                    <th class="text-center" >Size</th>
                                                    <th class="text-center" >Màu</th>
                                                    <th class="text-center" >Mở Bán</th>
                                                    <th class="text-center" >Trạng Thái</th>
                                                    <th class="text-center" >Số Lượng Hàng</th>
                                                    <th class="text-center" >Hoạt Động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                <tr>
                                                    <td>{{$product->id}}</td>
                                                    <td class="pname">
                                                        <div class="image">
                                                            <img src="{{asset('uploads/products/thumbnails')}}/{{$product->image}}" alt="{{$product->name}}" class="image">
                                                        </div>
                                                        <div class="name">
                                                            <a href="#" class="body-title-2">{{$product->name}}</a>
                                                            <div class="text-tiny mt-3">{{$product->slug}}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ number_format($product->regular_price, 0, ',', '.') }} đ</td>
                                                    <td>{{ number_format($product->sale_price, 0, ',', '.') }} đ</td>
                                                    <td>{{$product->SKU}}</td>
                                                    <td>{{$product->Category->name}}</td>
                                                    <td>{{$product->brand->name}}</td>
                                                  <td>
                                                      @php
                                                          $sizes = json_decode($product->sizes, true) ?? [];
                                                      @endphp
                                                  
                                                      {{ implode(', ', array_map('strtoupper', $sizes)) }}
                                                  </td>
                                                  <td>
                                                      {{ implode(', ', json_decode($product->colors, true) ?? []) }}
                                                  </td>     
                                                    <td>{{$product->featured == 0? "không":"có"}}</td>
                                                    <td>{{$product->stock_status}}</td>
                                                    <td>{{$product->quantity}}</td>
                                                    <td>
                                                        <div class="list-icon-function">
                                                            <a href="#" target="_blank">
                                                                <div class="item eye">
                                                                    <i class="icon-eye"></i>
                                                                </div>
                                                            </a>
                                                            <a href="{{ route('admin.product.edit', ['id'=>$product->id]) }}">
                                                                <div class="item edit">
                                                                    <i class="icon-edit-3"></i>
                                                                </div>
                                                            </a>
                                                            <form action="{{route('admin.product.delete', ['id'=>$product->id])}}" method="POST">
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

                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                                        {{$products->links('pagination::bootstrap-5')}}


                                    </div>
                                </div>
                            </div>
                        </div>
@endsection
@push('scripts')
<script>
    $(function(){
    $(".delete").on('click', function(e){
        e.preventDefault();
        var selectedForm = $(this).closest('form');
        swal({
            title: "Bạn Chắc chắn?",
            text: "Muốn Xóa Không?",
            type:"Cảnh Báo",
            buttons:["Không","Có"],
            confirmButtonColor: '#dc3545',
        }).then(function (result) {
            if (result) {
                selectedForm.submit();
            }
        });
    });
});

</script>
@endpush