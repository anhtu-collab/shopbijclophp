@extends('layouts.app')
@section('content')
 <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Thông tin tài khoản</h2>
      <div class="row">
        <div class="col-lg-3">
          <ul class="account-nav">
            <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.index') ? 'menu-link_active' : '' }}">Bảng điều Khiển</a></li>
            <li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.orders') ? 'menu-link_active' : '' }}">Đơn Hàng</a></li>

            <li><a href="{{ route('user.address') }}" class="menu-link menu-link_us-s {{ request()->routeIs('user.address') ? 'menu-link_active' : '' }}">Địa Chỉ </a></li>

            <li><a href="{{ route('user.details') }}" class="menu-link menu-link_us-s">Thông Tin Tài Khoản</a></li>

            <li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s {{ request()->routeIs('wishlist.index') ? 'menu-link_active' : '' }}">Danh sách yêu thích</a></li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-link menu-link_us-s" style="border:none;background:none;">
                    ĐĂNG XUẤT
                    </button>
                </form>
            </li>
          </ul>
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
              <form name="account_edit_form" action="{{ route('user.update') }}" method="POST" class="needs-validation" novalidate="">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" placeholder="Full Name" name="name" value="{{ auth()->user()->name }}" required="">
                      <label for="name">Tên</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ auth()->user()->mobile }}"required="">
                      <label for="mobile">Số Điện Thoại</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ auth()->user()->email }}"required="">
                      <label for="account_email">Email</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <h5 class="text-uppercase mb-0">Thay đổi mật khẩu</h5>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" id="old_password" name="old_password"
                        placeholder="Old password">
                      <label for="old_password">Mật khẩu cũ</label>

                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" id="new_password" name="new_password"
                        placeholder="New password">
                      <label for="account_new_password">Mật khẩu mới</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" cfpwd="" data-cf-pwd="#new_password"
                        id="new_password_confirmation" name="new_password_confirmation"
                        placeholder="Confirm new password">
                      <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                      <div class="invalid-feedback">Mật khẩu không khớp!</div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection