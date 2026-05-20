@extends('layouts.admin')
@section('content')
<style>
.tag-chip{
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    color: #fff;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.tag-size{
    background: #343a40;
}

.tag-color{
    background: #0d6efd;
}

.tag-chip i{
    font-style: normal;
    cursor: pointer;
    font-weight: bold;
    
}
.size-item-box{
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 10px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    position: relative;
}

.size-label{
    font-weight: 600;
    background: #343a40;
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.size-item-box input{
    width: 70px;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 3px 6px;
    text-align: center;
}

.btn-remove-item{
    position: absolute;
    top: -6px;
    right: -6px;
    width: 18px;
    height: 18px;
    background: red;
    color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 12px;
    cursor: pointer;
}
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center mb-24 justify-between gap20 flex-wrap">
            <h3>THÊM SẢN PHẨM</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{route('admin.index')}}"><div class="text-tiny">Bảng Điều khiển</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{route('admin.products')}}"><div class="text-tiny">Tất Cả Sản Phẩm</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Thêm Sản Phẩm</div></li>
            </ul>
        </div>
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product.store')}}">
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Tên Sản Phẩm<span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Nhập tên sản phẩm" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">Mã Sản Phẩm<span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Nhập mã sản phẩm" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Danh Mục <span class="tf-color-1">*</span></div>
                        <div class="select">
                           <select name="category_id" required>
                           <option value="" disabled selected>--Chọn danh mục--</option>

                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                             @endforeach

                              </select>
                        </div>
                    </fieldset>
                    @error('category_id') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                    <fieldset class="brand">
                        <div class="body-title mb-10">Thương Hiệu <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="brand_id" required>
                             <option value="" disabled selected>--Chọn thương hiệu--</option>

                              @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach

                           </select>
                        </div>
                    </fieldset>
                    @error('brand_id') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Thông Tin <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="short_description" placeholder="Nhập nội dung chi tiết" tabindex="0" aria-required="true" required="">{{old('short_description')}}</textarea>
                </fieldset>
                @error('short_description') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <fieldset class="description">
                    <div class="body-title mb-10">Mô Tả <span class="tf-color-1">*</span></div>
                    <textarea class="mb-10" name="description" placeholder="Nhập mô tả chi tiết." tabindex="0" aria-required="true" required="">{{old('description')}}</textarea>
                </fieldset>
                @error('description') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title mb-10">Ảnh Sản Phấm Gốc<span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="preview.jpg" class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon"><i class="icon-upload-cloud"></i></span>
                                <span class="text-tiny">Chọn ảnh <span class="tf-color">bấm vào đây</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <fieldset>
                    <div class="body-title mb-10">Ảnh Sản Phẩm <span class="tf-color-1">*</span></div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="flex-grow">
                            <div class="upload-image">
                                <div id="galpreview" class="flex-grow flex-wrap gap10" style="display:flex"></div>
                                <div id="g-upload-file" class="item up-load">
                                    <label class="uploadfile" for="gFile">
                                        <span class="icon"><i class="icon-upload-cloud"></i></span>
                                        <span class="text-tiny">Chọn ảnh <span class="tf-color">Bấm vào đây</span></span>
                                        <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                @error('images') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Giá Gốc <span class="tf-color-1">*</span></div>
                        <input class="mb-10 price-input" type="text" name="regular_price" placeholder="Nhập giá gốc" name="regular_price" value="{{old('regular_price')}}">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giá Giảm <span class="tf-color-1">*</span></div>
                        <input class="mb-10 price-input" type="text" name="sale_price" placeholder="Nhập giá đã giảm" name="sale_price" value="{{old('sale_price')}}">
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Mã Sản Phẩm <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Nhập mã sản phẩm" name="SKU" value="{{old('SKU')}}">
                    </fieldset>
                    <!-- <fieldset class="name">
                        <div class="body-title mb-10">Số Lượng Hàng <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="nhập số lượng hàng" name="quantity" value="{{old('quantity')}}">
                    </fieldset> -->
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Trạng Thái <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="stock_status">
                                <option value="instock">Còn hàng</option>
                                <option value="outofstock">Hết hàng</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Đang mở bán <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="featured">
                                <option value="0">Không</option>
                                <option value="1">Có</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="mb-4">
                <div class="body-title mb-2 fw-bold">
                    Kích thước & Số lượng <span class="text-danger">*</span>
                </div>

                <div class="d-flex gap-2 mb-3">
                    <!-- size -->
                    <input type="text" id="sizeInput" class="form-control" placeholder="Vui lòng nhập kích thước và số lượng rồi Enter" style="max-width:220px">

                    <input type="number" id="stockInput" class="form-control" placeholder="Số lượng" min="1" value="1" style="max-width:120px">
                        

                    <!-- add button -->
                    <button type="button" class="btn btn-primary text-nowrap" onclick="addSize()">
                        Thêm
                    </button>
                </div>

                <!-- list hiển thị các size đã thêm -->
                <div id="sizeList" class="d-flex flex-wrap gap-2 mb-2"></div>

                <!-- hidden input chứa JSON để gửi lên backend -->
                <input type="hidden" name="sizes" id="sizes" value="[]">
            </div>
                    <div class="mb-3">
                         <div class="body-title mb-10">Màu sắc <span class="tf-color-1">*</span></div>
                        <input type="text" id="colorInput" class="form-control" placeholder="Nhập màu cho sản phẩm">
                    
                        <div id="colorList" class="mt-2 d-flex flex-wrap gap-2"></div>
                    
                        <input type="hidden" name="colors" id="colors">
                    </div>
                        
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Lưu</button>
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

        // Xem trước gallery ảnh
        $("#gFile").on("change", function(e){
            const gphotos = this.files;
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

    str = str.normalize("NFD").replace(/[\u0300-\u036f]/g, ""); // bỏ dấu
    str = str.replace(/đ/g, "d");

    str = str.replace(/[^a-z0-9\s-]/g, "");
    str = str.replace(/\s+/g, "-");
    str = str.replace(/-+/g, "-");

    return str.trim("-");
}
    let sizes = [];
let colors = [];

// $("#sizeInput").on("keypress", function(e){
//     if(e.which === 13){
//         e.preventDefault();

//         let val = $(this).val().trim();
//         if(val && !sizes.includes(val)){
//             sizes.push(val);
//             renderSizes();
//         }
//         $(this).val('');
//     }
// });
$("#stockInput").on("keypress", function(e){
    if(e.which === 13){
        e.preventDefault();
        addSize();
    }
});

function addSize() {
    let size = $("#sizeInput").val().trim().toUpperCase();
    let quantity = $("#stockInput").val().trim();

    if (size && quantity) {
        // Kiểm tra nếu size đã tồn tại thì tăng số lượng thay vì thêm mới (tùy chọn)
        let existing = sizes.find(s => s.size === size);
        if (existing) {
            existing.quantity = parseInt(existing.quantity) + parseInt(quantity);
        } else {
            sizes.push({
                size: size,
                quantity: quantity
            });
        }

        renderSizes();
        $("#sizeInput").val('').focus();
        $("#stockInput").val('');
    }
}

function renderSizes() {
    $("#sizeList").html('');

    sizes.forEach((s, index) => {
        $("#sizeList").append(`
            <div class="size-item-box">
                <span class="btn-remove-item" onclick="removeSize(${index})">×</span>
                <span class="size-label">${s.size}</span>
                <input type="number" 
                       value="${s.quantity}" 
                       min="1" 
                       onchange="updateQuantity(${index}, this.value)"
                       placeholder="SL">
            </div>
        `);
    });

    // Cập nhật input hidden để gửi backend
    $("#sizes").val(JSON.stringify(sizes));
}
function updateQuantity(index, newQty) {
    if (newQty < 1) newQty = 1;
    sizes[index].quantity = newQty;
    
    // Cập nhật lại input hidden mà không cần render lại giao diện (để tránh mất focus)
    $("#sizes").val(JSON.stringify(sizes));
}

function removeSize(index){
    sizes.splice(index, 1);
    renderSizes();
}

$("#colorInput").on("keypress", function(e){
    if(e.which === 13){
        e.preventDefault();

        let val = $(this).val().trim();
        if(val && !colors.includes(val)){
            colors.push(val);
            renderColors();
        }
        $(this).val('');
    }
});

function renderColors(){
    $("#colorList").html('');

    colors.forEach((c, index) => {
        $("#colorList").append(`
            <span class="tag-chip tag-color">
                ${c}
                <i onclick="removeColor(${index})">×</i>
            </span>
        `);
    });

    $("#colors").val(JSON.stringify(colors));
}

function removeColor(index){
    colors.splice(index, 1);
    renderColors();
}
$('.price-input').on('input', function () {
    let value = $(this).val().replace(/\D/g, ''); // chỉ lấy số
    value = new Intl.NumberFormat('vi-VN').format(value);
    $(this).val(value);
});
    
</script>
@endpush