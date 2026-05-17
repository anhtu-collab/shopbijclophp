@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Tất Cả Bài Viết</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{route('admin.index')}}">
                            <div class="text-tiny">Bảng Điều Khiển</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Tất Cả Bài Viết</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm Kiếm..." name="search" tabindex="2">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{route('admin.blog.add')}}">
                        <i class="icon-plus"></i>Thêm
                    </a>
                </div>

                @if(Session::has('status'))
                    <p class="alert alert-success mt-10">{{ Session::get('status') }}</p>
                @endif

                <div class="wg-table table-all-user mt-20">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Ảnh Bìa</th>
                                <th class="text-center">Tiêu Đề</th>
                                <th class="text-center">Danh Mục</th>
                                <th class="text-center">Tác Giả</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Ngày Tạo</th>
                                <th class="text-center">Hoạt động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                                <tr>
                                    <td>{{ ($blogs->currentPage() - 1) * $blogs->perPage() + $loop->iteration }}</td>
                                    <td>
                                        @if($blog->thumbnail)
                                            <img src="{{ asset('uploads/blogs/' . $blog->thumbnail) }}"
                                                style="width:60px;height:60px;object-fit:cover;border-radius:6px;" alt="">
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($blog->title, 50) }}</td>
                                    <td>{{ $blog->category ?? '—' }}</td>
                                    <td>{{ $blog->author ?? '—' }}</td>
                                    <td>
                                        @if($blog->status == 1)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td>{{ $blog->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.blog.edit', $blog->id) }}">
                                                <div class="item edit"><i class="icon-edit-3"></i></div>
                                            </a>
                                            <form action="{{ route('admin.blog.delete', $blog->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="item text-danger delete">
                                                    <i class="icon-trash-2"></i>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Chưa có bài viết nào!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('.delete').on('click', function (e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Bạn Chắc chắn?",
                    text: "Muốn Xóa Không?",
                    type:"Cảnh Báo",
                    buttons:["Không","Có"],
                }).then(function (result) {
                    if (result) { form.submit(); }
                });
            });
        });
    </script>
@endpush
