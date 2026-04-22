@extends('layouts.admin')

@section('content')
   <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>THÊM DANH MỤC</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Bảng Điều khiển</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.categories')}}">
                                                <div class="text-tiny">Danh Mục</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Thêm Danh Mục</div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- new-category -->
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf  
                                        <fieldset class="name">
                                            <div class="body-title">Tên Danh Mục <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập tên danh mục" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                                        </fieldset>
                                          @error('name') <span class="alert-danger text-center">{{$message}}</span> @enderror 
                                        <fieldset class="name">
                                            <div class="body-title">Mã Danh Mục <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập mã danh mục" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                                        </fieldset>
                                          @error('slug') <span class="alert-danger text-center">{{$message}}</span>  @enderror 
                                        <fieldset>
                                            <div class="body-title">Ảnh Danh Mục<span class="tf-color-1">*</span>
                                            </div>
                                            <div class="upload-image flex-grow">
                                                <div class="item" id="imgpreview" style="display:none">
                                                    <img src="upload-1.html" class="effect8" alt="">
                                                </div>
                                                <div id="upload-file" class="item up-load">
                                                    <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Chọn ảnh <span
                                                                class="tf-color">bấm vào đây</span></span>
                                                        <input type="file" id="myFile" name="image" accept="image/*">
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                          @error('image') <span class="alert-danger text-center">{{$message}}</span>  @enderror 

                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
    
@endsection
@push('scripts')
    <script>
    $(function(){
        $("#myFile").on("change",function(){
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr('src', URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        $("input[name='name']").on("input",function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(str) {
        str = str.toLowerCase();

        // bỏ dấu tiếng Việt
        str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

        // đ -> d
        str = str.replace(/đ/g, 'd');

        // xóa ký tự đặc biệt
        str = str.replace(/[^a-z0-9\s-]/g, '');

        // bỏ khoảng trắng đầu/cuối + thay space = -
        str = str.trim().replace(/\s+/g, '-');

        return str;
    }
     </script>    
@endpush