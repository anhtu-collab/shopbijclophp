@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chỉnh Sửa Bài Viết</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{route('admin.index')}}">
                            <div class="text-tiny">Bảng Điều Khiển</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{route('admin.blogs')}}">
                            <div class="text-tiny">Tất Cả Bài Viết</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh Sửa Bài Viết</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.blog.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $blog->id }}">

                    <fieldset class="name">
                        <div class="body-title">Tiêu Đề <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="title" value="{{ old('title', $blog->title) }}"
                            placeholder="Tiêu đề bài viết" required>
                    </fieldset>
                    @error('title')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                    <fieldset class="category">
                        <div class="body-title">Danh Mục</div>
                        <div class="select flex-grow">
                            <select name="category">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach(['Sản phẩm mới', 'Thương hiệu', 'Chất liệu vải', 'Xu hướng thời trang', 'Khuyến mãi', 'Tin tức'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $blog->category) == $cat ? 'selected' : '' }}>
                                        {{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Mô Tả Ngắn</div>
                        <textarea class="flex-grow" name="excerpt" rows="3"
                            style="width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px;">{{ old('excerpt', $blog->excerpt) }}</textarea>
                    </fieldset>

                    <fieldset>
                        <div class="body-title">Nội Dung <span class="tf-color-1">*</span></div>
                        <textarea name="content" id="content" rows="10"
                            style="width:100%;padding:10px;border:1px solid #e5e7eb;border-radius:8px;">{{ old('content', $blog->content) }}</textarea>
                    </fieldset>
                    @error('content')<span class="alert alert-danger text-center">{{ $message }}</span>@enderror

                    <fieldset>
                        <div class="body-title">Ảnh Chủ Đề</div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{ $blog->thumbnail ? '' : 'display:none;' }}">
                                <img src="{{ $blog->thumbnail ? asset('uploads/blogs/' . $blog->thumbnail) : '' }}"
                                    class="effect8" alt="" />
                            </div>
                            <div class="item up-load">
                                <label class="uploadfile" for="thumbnail">
                                    <span class="icon"><i class="icon-upload-cloud"></i></span>
                                    <span class="body-text">Chọn ảnh <span class="tf-color">bấm vào đây</span></span>
                                    <input type="file" id="thumbnail" name="thumbnail">
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="category">
                        <div class="body-title">Trạng Thái</div>
                        <div class="select flex-grow">
                            <select name="status">
                                <option value="1" {{ old('status', $blog->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('status', $blog->status) == '0' ? 'selected' : '' }}>Không hoạt động
                                </option>
                            </select>
                        </div>
                    </fieldset>

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
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
        $(function () {
            $("#thumbnail").on("change", function () {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
        });
    </script>
@endpush