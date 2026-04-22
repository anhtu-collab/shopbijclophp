@extends('layouts.app')
@section('content')
 <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">Địa Chỉ</h2>
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
          <div class="page-content my-account__address">
            <div class="row">
              <div class="col-6">
                <p class="notice">Các địa chỉ dưới đây sẽ được sử dụng mặc định tại trang thanh toán.</p>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('user.address.add') }}" class="btn btn-sm btn-info text-black">Thêm địa chỉ mới</a>
                
            </div>
        <div class="my-account__address-list row">
            <h5>Địa chỉ giao hàng</h5>
            </div>
             @foreach($addresses as $address)
             
                <div class="my-account__address-item__detail">
                     <div>
                        <p><strong>Họ tên:</strong> {{ $address->name ?? '---' }}</p>
    
                            <p><strong>Địa chỉ:</strong> 
                                {{ $address->address ?? '---' }}
                            </p>

                            <p><strong>Khu vực:</strong> 
                                {{ $address->locality ?? '---' }}
                            </p>

                            <p><strong>Thành phố:</strong> 
                                {{ $address->city ?? '---' }}
                            </p>

                            @if($address->landmark)
                                <p><strong>Ghi chú:</strong> {{ $address->landmark }}</p>
                            @else
                                <p><strong>Ghi chú:</strong> ---</p>
                            @endif

                            <p><strong>Mã bưu điện (ZIP):</strong> {{ $address->zip ?? '---' }}</p>
                            
                            <br>
                            
                            <p><strong>Số điện thoại:</strong> 
                                <span class="text-dark">{{ $address->phone ?? '---' }}</span>
                            </p>
                             <div>
              <a href="{{ route('user.address.edit', $address->id) }}" 
                   class="btn btn-warning btn-sm mt-2">
                   Sửa thông tin
                </a>
            </div>
                    </div>
                </div>
              </div>
              <hr>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection