@extends('layouts.app')

@section('content')
    <div class="mb-4 pb-4"></div>
    <section class="container" style="max-width:860px;">


        <nav class="mb-4" style="font-size:13px;">
            <a href="{{ route('home.index') }}" class="text-muted text-decoration-none">Home</a>
            <span class="mx-2 text-muted">/</span>
            <a href="{{ route('views.blogs') }}" class="text-muted text-decoration-none">Blog</a>
            <span class="mx-2 text-muted">/</span>
            <span>{{ Str::limit($blog->title, 40) }}</span>
        </nav>


        <div class="d-flex align-items-center gap-3 mb-3">
            @if($blog->category)
                <span class="badge rounded-pill text-dark px-3 py-1 fw-medium" style="background:#f0f0f0;font-size:12px;">
                    {{ $blog->category }}
                </span>
            @endif
            <span class="text-muted" style="font-size:13px;">
                {{ $blog->created_at->format('d M, Y') }}
            </span>
            <span class="text-muted" style="font-size:13px;">
                 {{ $blog->author ?? 'Admin' }}
            </span>
        </div>


        <h1 class="fw-bold mb-4" style="font-size:32px;line-height:1.3;">{{ $blog->title }}</h1>


        @if($blog->excerpt)
            <p class="lead text-muted mb-4" style="font-size:16px;border-left:4px solid #000;padding-left:16px;">
                {{ $blog->excerpt }}
            </p>
        @endif


        @if($blog->thumbnail)
            <div class="mb-5" style="border-radius:16px;overflow:hidden;max-height:480px;">
                <img src="{{ asset('uploads/blogs/' . $blog->thumbnail) }}" class="w-100"
                    style="object-fit:cover;max-height:480px;" alt="{{ $blog->title }}">
            </div>
        @endif


        <div class="blog-content mb-5" style="font-size:16px;line-height:1.9;color:#333;">
            {!! $blog->content !!}
        </div>


        <div class="d-flex align-items-center gap-3 py-4 border-top border-bottom mb-5">
            <span class="fw-medium">Chia sẻ:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                class="text-dark text-decoration-none">
                <svg width="20" height="20" viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_facebook" />
                </svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                target="_blank" class="text-dark text-decoration-none">
                <svg width="20" height="20" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_twitter" />
                </svg>
            </a>
        </div>
    </section>

   
    @if($related->count() > 0)
        <section class="container mb-5">
            <h4 class="fw-bold mb-4">Bài viết liên quan</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($related as $item)
                    <div class="col">
                        <a href="{{ route('home.blog.detail', $item->slug) }}" class="text-decoration-none text-dark">
                            <div class="card border-0 shadow-sm h-100" style="border-radius:12px;overflow:hidden;">
                                @if($item->thumbnail)
                                    <img src="{{ asset('uploads/blogs/' . $item->thumbnail) }}" style="height:180px;object-fit:cover;"
                                        alt="{{ $item->title }}">
                                @endif
                                <div class="card-body p-3">
                                    <span class="text-muted" style="font-size:12px;">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                    <h6 class="fw-semibold mt-1">{{ Str::limit($item->title, 55) }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif


    @push('styles')
        <style>
            .blog-content img {
                max-width: 100%;
                border-radius: 8px;
                margin: 16px 0;
            }

            .blog-content h2,
            .blog-content h3 {
                font-weight: 700;
                margin-top: 32px;
                margin-bottom: 12px;
            }

            .blog-content p {
                margin-bottom: 16px;
            }

            .blog-content ul,
            .blog-content ol {
                padding-left: 24px;
                margin-bottom: 16px;
            }

            .blog-content blockquote {
                border-left: 4px solid #000;
                padding: 12px 20px;
                background: #f9f9f9;
                margin: 24px 0;
                font-style: italic;
            }
        </style>
    @endpush
@endsection