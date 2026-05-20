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
                    <!-- <fieldset class="name">
                        <div class="body-title mb-10">Số Lượng<span class="tf-color-1">*</span></div>
                        <input type="text" name="quantity" value="{{$product->quantity}}">
                    </fieldset> -->
                                 </div>
                                <div class="mb-3">
                                    <div class="body-title mb-10">Kích thước & Số lượng</div>

                                    <div class="d-flex gap-2 mb-2">
                                        <input type="text" id="sizeInput" class="form-control" placeholder="Vui lòng nhập kích thước và số lượng rồi Enter" style="max-width:250px">
                                        <input type="number" id="stockInput" class="form-control" placeholder="Số lượng" min="1" value="1" style="max-width:120px">
                                        <button type="button" class="btn btn-primary" onclick="addSize()">Thêm</button>
                                    </div>

                                    <div id="sizeList" class="d-flex flex-wrap gap-2"></div>

                                    <input type="hidden" name="sizes" id="sizes">
                                </div>

                        <div class="mb-3">
                            <div class="body-title mb-10">Màu sắc</div>
                            <input type="text" id="colorInput" class="form-control" placeholder="Vui lòng nhập màu sắc rồi Enter">

                            <div id="colorList" class="mt-2 d-flex flex-wrap gap-2"></div>

                            <input type="hidden" name="colors" id="colors">
                        </div>

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
        // Xem trước ảnh đại diện chính khi thay đổi file
        $("#myFile").on("change", function(e){
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        // Xem trước danh sách bộ sưu tập ảnh (Gallery)
        $("#gFile").on("change", function(e){
            const gphotos = this.files;
            // $("#galpreview").html(""); 
            $.each(gphotos, function(key, val){
                $("#galpreview").append(`<div class="item gitems"><img src="${URL.createObjectURL(val)}"></div>`);
            });
        });

        // Tự động cập nhật Slug theo Tên sản phẩm
        $("input[name='name']").on("change", function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
        
    });

    // Hàm chuyển đổi tiếng Việt có dấu thành chuỗi Slug không dấu
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

    // Tự động định dạng dấu chấm phân tách phần nghìn cho giá tiền
    $('.price-input').on('input', function () {
        let value = $(this).val().replace(/\D/g, ''); // chỉ lấy số
        value = new Intl.NumberFormat('vi-VN').format(value);
        $(this).val(value);
    });

    // =======================================================
    // 1. XỬ LÝ BIẾN THỂ SIZE & SỐ LƯỢNG (ĐỒNG BỘ TỪ BẢNG VARIANTS)
    // =======================================================
    
    // Nạp dữ liệu Size & Số lượng cũ của sản phẩm từ mối quan hệ bảng ProductVariant
  let sizes = @json($oldSizes ?? []);

    // Sự kiện nhấn Enter tại ô nhập số lượng kho để kích hoạt thêm nhanh
    $("#stockInput").on("keypress", function(e){
        if(e.which === 13){
            e.preventDefault();
            addSize();
        }
    });
    

    // Hàm thêm mới hoặc cộng dồn số lượng Size
    function addSize() {
        let size = $("#sizeInput").val().trim().toUpperCase();
        let quantity = $("#stockInput").val().trim();

        if (size && quantity) {
            let qtyInt = parseInt(quantity);
            if (isNaN(qtyInt) || qtyInt <= 0) {
                alert("Số lượng phải là số nguyên lớn hơn 0");
                return;
            }

            // Nếu size đã tồn tại trong danh sách, tiến hành cộng dồn số lượng
            let existing = sizes.find(s => s.size === size);
            if (existing) {
                existing.quantity = parseInt(existing.quantity) + qtyInt;
            } else {
                // Nếu chưa có, đẩy Object mới vào mảng
                sizes.push({
                    size: size,
                    quantity: qtyInt
                });
            }

            renderSizes();
            $("#sizeInput").val('').focus();
            $("#stockInput").val('');
        }
    }

    // Hàm hiển thị danh sách Size kèm ô chỉnh sửa số lượng trực quan
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

        // Đồng bộ mảng Object thành chuỗi JSON nạp vào input ẩn gửi lên Backend
        $("#sizes").val(JSON.stringify(sizes));
    }

    // Hàm cập nhật số lượng trực tiếp khi người dùng thay đổi giá trị trong ô input của box
    function updateQuantity(index, newQty) {
        let qtyInt = parseInt(newQty);
        if (isNaN(qtyInt) || qtyInt < 1) qtyInt = 1;
        
        sizes[index].quantity = qtyInt;
        
        // Cập nhật giá trị chuỗi JSON mà không cần render lại giao diện (để giữ con trỏ focus của người dùng)
        $("#sizes").val(JSON.stringify(sizes));
    }

    // Hàm xóa Size khỏi danh sách
    function removeSize(index){
        sizes.splice(index, 1);
        renderSizes();
    }

    // =======================================================
    // 2. XỬ LÝ BIẾN THỂ MÀU SẮC (COLOR)
    // =======================================================
    
    // Nạp danh sách tên màu cũ của sản phẩm từ bảng ProductVariant
   let colors = @json($oldColors ?? []);


   $(document).ready(function(){
    renderSizes();
    renderColors();

    // Chốt chặn cuối cùng kiểm tra dữ liệu trước khi submit form
    $("form").on("submit", function(e) {
        // Kiểm tra xem mảng dữ liệu size có bị rỗng không
        if (typeof sizes === 'undefined' || sizes.length === 0) {
            alert("Vui lòng thêm ít nhất một Size và Số lượng trước khi lưu sản phẩm!");
            e.preventDefault(); // Dừng việc gửi form lại
            return false;
        }

        // Ép dữ liệu mảng đối tượng thành chuỗi JSON nạp vào input ẩn
        $("#sizes").val(JSON.stringify(sizes));
        $("#colors").val(JSON.stringify(colors));
        
        return true; 
    });
});
    

    // Sự kiện nhấn Enter tại ô Màu sắc để thêm nhanh
    $("#colorInput").on("keypress", function(e){
        if(e.which === 13){
            e.preventDefault();

            let val = $(this).val().trim();
            // Chuẩn hóa chữ cái đầu viết hoa cho màu sắc đẹp hơn (ví dụ: trắng -> Trắng)
            if (val) {
                val = val.charAt(0).toUpperCase() + val.slice(1);
            }

            if(val && !colors.includes(val)){
                colors.push(val);
                renderColors();
            }
            $(this).val('');
        }
    });

    // Hàm hiển thị danh sách Màu sắc dạng Tag-chip
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

        // Nạp chuỗi JSON mảng màu vào input ẩn gửi lên Backend
        $("#colors").val(JSON.stringify(colors));
    }

    // Hàm xóa Màu sắc khỏi danh sách
    function removeColor(index){
        colors.splice(index, 1);
        renderColors();
    }
</script>
@endpush