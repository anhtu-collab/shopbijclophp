@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
                            <!-- main-content-wrap -->
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>SỬA ẢNH QUẢNG CÁO</h3>
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
                                            <a href="{{route('admin.slide.add')}}">
                                                <div class="text-tiny">Tất Cả ẢNh Quảng Cáo</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Sửa Ảnh Quảng Cáo</div>
                                        </li>
                                    </ul>
                                </div>
                            
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="{{ route('admin.slide.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $slide->id }}" />
                                        <fieldset class="name">
                                            <div class="body-title">Khẩu hiệu<span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập khẩu hiệu" name="tagline" tabindex="0" value="{{$slide->tagline}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('tagline')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        <fieldset class="name">
                                            <div class="body-title">Tiêu Đề<span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập tiêu đề" name="title" tabindex="0" value="{{$slide->title}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('title')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        <fieldset class="name">
                                            <div class="body-title">Mô Tả  <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Nhập mô tả " name="subtitle" tabindex="0" value="{{$slide->subtitle}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('subtitle')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        <fieldset class="name">
                                        <div class="body-title">Link <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder=" Nhập link" name="link" tabindex="0" value="{{$slide->link}}" aria-required="true" required="">
                                        </fieldset>
                                        @error('link')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        <fieldset>
                                            <div class="body-title">Ảnh Quảng Cáo <span class="tf-color-1">*</span>
                                            </div>
                                            @if($slide->image)
                                            <div class="upload-image flex-grow">
                                                <div class="item" id="imgpreview" style="display:none;">
                                                    <img src="{{ asset('uploads/slides') }}/{{ $slide->image }}" class="effect8" alt=""/>
                                                </div>
                                                @endif
                                                <div class="item up-load">
                                                    <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Chọn ảnh<span class="tf-color"> bấm vào đây</span></span>
                                                        <input type="file" id="myFile" name="image">
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @error('image')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
                                        <fieldset class="category">
                                            <div class="body-title">Trạng thái <span class="tf-color-1">*</span></div>
                                            <div class="select flex-grow">
                                                <select class="" name="status">
                                                    <option value="">--chọn--</option>
                                                    <option value="1" @if($slide->status=="1") selected @endif>Hiển Thị</option>
                                                    <option value="0" @if($slide->status=="0") selected @endif>Ẩn</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                        @error('status')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror
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
           $("#myFile").on("change",function(e){
            const photoInp = $("#myFile");
             const [file] = this.files;
             if(file)
             {
                $("#imgpreview img").attr('src',URL.createObjectURL(file));
                $("#imgpreview").show();
             }
           });
    
        });
     </script>    
@endpush