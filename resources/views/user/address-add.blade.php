@extends('layouts.app')
@section('content')
 <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Thêm địa chỉ</h2>
      <div class="row">
        <div class="col-lg-3">
          <ul class="account-nav">
                    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Bảng điều khiển</a></li>
                    <li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s">Đơn hàng</a></li>
                    <li><a href="{{ route('user.address') }}" class="menu-link menu-link_us-s">Địa chỉ</a></li>
                    <li><a href="{{ route('user.details') }}" class="menu-link menu-link_us-s">Thông tin tài khoản</a></li>
                    <li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s">Danh sách yêu thích</a></li>
                    <li>
                        <a href="{{ route('logout') }}" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="menu-link menu-link_us-s">
                 Đăng xuất
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
          </ul>
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__address">
              <div class="row">
                  <div class="col-6">
                      <p class="notice">Các địa chỉ sau đây sẽ được sử dụng mặc định trên trang thanh toán.</p>
                  </div>
                  <div class="col-6 text-right">
                      <a href="{{ route('user.index') }}" class="btn btn-sm btn-danger">Quay lại</a>
                  </div>
              </div>

              <div class="row">
                  <div class="col-md-8">
                      <div class="card mb-5">
                          <div class="card-header">
                              <h5>Thêm địa chỉ</h5>
                          </div>
                          <div class="card-body">
                              <form action="{{ route('user.address.store') }}" method="POST">
                                @csrf
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                                                <label for="name">Họ và Tên*</label>

                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">
                                                <label for="phone">Số điện Thoại *</label>

                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="zip" id="zip" value="{{ old('zip') }}">
                                                <label for="zip">Mã bưu Điện*</label>

                                                @error('zip')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                          </div>
                                      </div>                        
                                      <div class="col-md-4">
                                          <div class="form-floating mt-3 mb-3">
                                              <input type="text" class="form-control" name="state" id="state" value="{{ old('state') }}">
                                                <label for="state">Xã *</label>

                                                @error('state')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                          </div>                            
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-floating my-3">
                                             <input type="text" class="form-control" name="city" id="city" value="{{ old('city') }}">
                                                    <label for="city">Thành Phố *</label>

                                                    @error('city')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}">
                                                <label for="address">Số nhà, tên tòa nhà  *</label>

                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-floating my-3">
                                              <input type="text" class="form-control" name="locality" id="locality" value="{{ old('locality') }}">
                                                    <label for="locality">Đường / Khu vực / Phường *</label>

                                                    @error('locality')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                          </div>
                                      </div>    
                                      <div class="col-md-12">
                                          <div class="form-floating my-3">
                                             <input type="text" class="form-control" name="landmark" id="landmark" value="{{ old('landmark') }}">
                                                <label for="landmark">Điểm nhận diện (gần đó có gì)*</label>

                                                @error('landmark')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                          </div>
                                      </div>  
                                      <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    value="1"
                                                    id="is_default"
                                                    name="is_default"
                                                    {{ old('is_default') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="is_default">
                                                    Đặt làm địa chỉ mặc định
                                                </label>
                                            </div>
                                        </div>
                                        </div>  
                                      <div class="col-md-12 text-right">
                                          <button type="submit" class="btn btn-success">Lưu</button>
                                      </div>                                     
                                  </div>
                              </form> 
                          </div>
                      </div>
                  </div>
              </div>
              <hr>                    
          </div>
      </div>
      </div>
    </section>
  </main>
@endsection