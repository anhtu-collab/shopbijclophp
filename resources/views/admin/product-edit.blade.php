@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center mb-24 justify-between gap20 flex-wrap">
            <h3>SỬA SẢN PHẨM</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{route('admin.index')}}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{route('admin.products')}}"><div class="text-tiny">Tất Cả Sản Phẩm</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Sửa Sản phẩm</div></li>
            </ul>
        </div>
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.update')}}">
            @csrf
            @method('PUT') <input type="hidden" name="id" value="{{$product->id}}">

            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Tên Sản Phẩm <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Nhập tên sản phẩm" name="name" value="{{$product->name}}" required="">
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Mã Sản Phẩm<span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Nhập mã sản phẩm" name="slug" value="{{$product->slug}}" required="">
                </fieldset>

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Danh Mục<span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="category_id">
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="brand">
                        <div class="body-title mb-10">Thương Hiệu <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="brand_id">
                                @foreach ($brands as $brand)
                                <option value="{{$brand->id}}" {{$product->brand_id == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Thông Tin <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="short_description" required="">{{$product->short_description}}</textarea>
                </fieldset>

                <fieldset class="description">
                    <div class="body-title mb-10">Mô Tả<span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" required="">{{$product->description}}</textarea>
                </fieldset>
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title mb-10">Ảnh Sản Phẩm Gốc<span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        @if($product->image)
                        <div class="item" id="imgpreview">
                            <img src="{{asset('uploads/products')}}/{{$product->image}}" class="effect8" alt="">
                        </div>
                        @endif
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                 <span class="text-tiny">Chọn ảnh <span class="tf-color">bấm vào đây</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title mb-10">Ảnh Sản Phẩm</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="flex-grow">
                            <div class="upload-image">
                                <div id="galpreview" class="flex-grow flex-wrap gap10" style="display:flex">
                                    @if($product->images)
                                        @foreach(explode(',', $product->images) as $img)
                                            <div class="item gitems">
                                                <img src="{{asset('uploads/products')}}/{{trim($img)}}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div id="g-upload-file" class="item up-load">
                                    <label class="uploadfile" for="gFile">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                         <span class="text-tiny">Chọn ảnh <span class="tf-color">bấm vào đây</span></span>
                                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Giá Gốc <span class="tf-color-1">*</span></div>
                        <input type="text" class="price-input" name="regular_price" value="{{ number_format($product->regular_price, 0, ',', '.') }}">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giá Giảm<span class="tf-color-1">*</span></div>
                        <input type="text" class="price-input" name="sale_price" value="{{ number_format($product->sale_price, 0, ',', '.') }}">
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Mã Sản Phẩm<span class="tf-color-1">*</span></div>
                        <input type="text" name="SKU" value="{{$product->SKU}}">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Số Lượng<span class="tf-color-1">*</span></div>
                        <input type="text" name="quantity" value="{{$product->quantity}}">
                    </fieldset>
                                 </div>
                                  <fieldset class="name">
                         <div class="body-title mb-10">Size<span class="tf-color-1">*</span></div>
                         <input type="text" name="sizes" class="form-control" value="{{ implode(', ', json_decode($product->sizes, true) ?? []) }}">
                     </fieldset>
                 
                     <fieldset class="name">
                         <div class="body-title mb-10">Màu Sắc<span class="tf-color-1">*</span></div>
                         <input type="text" name="colors" class="form-control" value="{{ implode(', ', json_decode($product->colors, true) ?? []) }}">
                     </fieldset>
                        

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Trạng Thái</div>
                        <div class="select">
                            <select name="stock_status">
                                <option value="instock" {{$product->stock_status == 'instock' ? 'selected' : ''}}>Còn Hàng</option>
                                <option value="outofstock" {{$product->stock_status == 'outofstock' ? 'selected' : ''}}>Hết hàng</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Đang Mở bán</div>
                        <div class="select">
                            <select name="featured">
                                <option value="0" {{$product->featured == 0 ? 'selected' : ''}}>Không</option>
                                <option value="1" {{$product->featured == 1 ? 'selected' : ''}}>Có</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){

        $("#myFile").on("change", function(e){
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });


        $("#gFile").on("change", function(e){
            const gphotos = this.files;
            $("#galpreview").html(""); 
            $.each(gphotos, function(key, val){
                $("#galpreview").append(`<div class="item gitems"><img src="${URL.createObjectURL(val)}"></div>`);
            });
        });

        $("input[name='name']").on("change", function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

   function StringToSlug(str) {
    str = str.toLowerCase();

    // bỏ dấu tiếng Việt
    str = str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    str = str.replace(/đ/g, "d");

    // xoá ký tự đặc biệt
    str = str.replace(/[^a-z0-9\s-]/g, "");

    // thay khoảng trắng thành dấu -
    str = str.replace(/\s+/g, "-");

    // xoá nhiều dấu - liên tiếp
    str = str.replace(/-+/g, "-");

    return str.trim("-");
}
$('.price-input').on('input', function () {
    let value = $(this).val().replace(/\D/g, '');
    value = new Intl.NumberFormat('vi-VN').format(value);
    $(this).val(value);
});
</script>
@endpush