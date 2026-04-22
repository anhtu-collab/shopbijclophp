@extends('layouts.app')
@section('content')
 <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container">
      <div class="mw-930">
        <h2 class="page-title">LIÊN HỆ</h2>
      </div>
    </section>

    <hr class="mt-2 text-secondary " />
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
      <div class="mw-930">
        <div class="contact-us__form">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
          <form name="contact-us-form" class="needs-validation" novalidate=""  action="{{ route('home.contact.store') }}" method="POST">
            @csrf
            <h3 class="mb-5">Gửi Tin Nhắn Cho Chúng Tôi</h3>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="name" placeholder="Name *" value="{{ old('name') }}" required="">
              <label for="contact_us_name">Họ và tên *</label>
              @error('name') <span class="text-danger">{{ $message }}</span> @enderror
              
            </div>
            <div class="form-floating my-4">
              <input type="text" class="form-control" name="phone" placeholder="Phone *" value="{{ old('phone') }}"required="">
              <label for="contact_us_name">Số điện thoại*</label>
              @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
              
            </div>
            <div class="form-floating my-4">
              <input type="email" class="form-control" name="email" placeholder="Email address *" value="{{ old('email') }}" required="">
              <label for="contact_us_name">Email *</label>
              @error('email') <span class="text-danger">{{ $message }}</span> @enderror
             
            </div>
            <div class="my-4">
              <textarea class="form-control form-control_gray" name="comment" placeholder="Nội dung tin nhắn..." value=" {{ old('comment') }}"cols="30"
                rows="8" required=""></textarea>
              @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="my-4">
              <button type="submit" class="btn btn-primary">GỬI</button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </main>

@endsection