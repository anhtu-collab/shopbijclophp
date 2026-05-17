@extends('layouts.app')
<style>
    .cart-header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 40px;
}

.btn-back{
    padding: 10px 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: 0.2s;
    font-weight: 500;
}

.btn-back:hover{
    background: #f3f3f3;
    transform: translateX(-2px);
}
    </style>
@section('content')
    {{-- Page Header --}}
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        
                              <div class="cart-header">
    <h2 class="page-title text-center mb-2">Blog</h2>

    <a href="{{ route('shop.index') }}" class="btn-back">
        Quay lại
    </a>
</div>
        <div class="text-center text-muted mb-5">Tin tức, xu hướng thời trang & câu chuyện thương hiệu</div>

        {{-- Filter danh mục --}}
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            <a href="{{ route('views.blogs') }}"
                class="btn btn-sm {{ !request('category') ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4">
                Tất cả
            </a>
            @foreach(['Sản phẩm mới', 'Thương hiệu', 'Chất liệu vải', 'Xu hướng thời trang', 'Khuyến mãi', 'Tin tức'] as $cat)
                <a href="{{ route('views.blogs', ['category' => $cat]) }}"
                    class="btn btn-sm {{ request('category') == $cat ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-4">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Danh sách bài viết --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            @forelse($blogs as $blog)
                <div class="col">
                    <a href="{{ route('views.blog.detail', $blog->slug) }}" class="text-decoration-none text-dark">
                        {{-- <a href="{{ route('views.blog.detail', $blog->slug) }}">{{ $blog->title }}</a> --}}
                        <div class="card border-0 h-100 shadow-sm"
                            style="border-radius:12px;overflow:hidden;transition:transform 0.2s;"
                            onmouseover="this.style.transform='translateY(-4px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            {{-- Thumbnail --}}
                            <div style="overflow:hidden;height:220px;">
                                @if($blog->thumbnail)
                                    <img src="{{ asset('uploads/blogs/' . $blog->thumbnail) }}" class="w-100 h-100"
                                        style="object-fit:cover;" alt="{{ $blog->title }}">
                                @else
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <span class="text-muted">No image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body p-4">
                                {{-- Category + Date --}}
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($blog->category)
                                        <span class="badge rounded-pill text-dark fw-medium px-3 py-1"
                                            style="background:#f0f0f0;font-size:11px;">
                                            {{ $blog->category }}
                                        </span>
                                    @endif
                                    <span class="text-muted" style="font-size:12px;">
                                        {{ $blog->created_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h5 class="fw-semibold mb-2" style="font-size:16px;line-height:1.4;">
                                    {{ Str::limit($blog->title, 60) }}
                                </h5>

                                {{-- Excerpt --}}
                                @if($blog->excerpt)
                                    <p class="text-muted mb-3" style="font-size:13px;line-height:1.6;">
                                        {{ Str::limit($blog->excerpt, 100) }}
                                    </p>
                                @endif

                                {{-- Author + Read more --}}
                                <div class="d-flex align-items-center justify-content-between mt-auto">
                                    <span class="text-muted" style="font-size:12px;">
                                        ✍️ {{ $blog->author ?? 'Admin' }}
                                    </span>
                                    <span style="font-size:13px;font-weight:600;color:#000;text-decoration:underline;">
                                        Đọc thêm →
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Chưa có bài viết nào.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mb-5">
            {{ $blogs->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection