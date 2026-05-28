@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thêm Người Dùng</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Bảng Điều Khiển</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{route('admin.users')}}">
                            <div class="text-tiny">Tất Cả Người Dùng</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm Người Dùng</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif

                <form class="form-new-product form-style-1" action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

    
                    <fieldset class="name">
                        <div class="body-title">Tên <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Họ và tên" name="name" tabindex="0"
                            value="{{ old('name') }}" aria-required="true" required>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

   
                    <fieldset class="name">
                        <div class="body-title">Email <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="email" placeholder="Email" name="email" tabindex="0"
                            value="{{ old('email') }}" aria-required="true" required>
                    </fieldset>
                    @error('email')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title">Số điện thoại</div>
                        <input class="flex-grow" type="text" placeholder="Số điện thoại" name="mobile" tabindex="0"
                            value="{{ old('mobile') }}">
                    </fieldset>
                    @error('mobile')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title">Mật khẩu <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="password" placeholder="Mật khẩu" name="password" tabindex="0"
                            aria-required="true" required>
                    </fieldset>
                    @error('password')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

        
                    <fieldset class="category">
                        <div class="body-title">Chức năng <span class="tf-color-1">*</span></div>
                        <div class="select flex-grow">
                            <select name="utype">
                                <option value="">-- Chọn --</option>
                                <option value="ADM" {{ old('utype') == 'ADM' ? 'selected' : '' }}>Admin</option>
                                <option value="USR" {{ old('utype') == 'USR' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('utype')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
