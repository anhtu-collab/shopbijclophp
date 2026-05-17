@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>TẤT CẢ REVIEW</h3>

            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Bảng Điều Khiển</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Quản Lý Review</div></li>
            </ul>
        </div>

        <div class="wg-box">

            {{-- SEARCH --}}
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Tìm user / sản phẩm..." name="keyword">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="wg-table table-all-user">
                <div class="table-responsive">

                    @if(Session::has('status'))
                        <p class="alert alert-success">{{Session::get('status')}}</p>
                    @endif

                    <table class="table table-striped table-bordered">

                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">Sản Phẩm</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Hoạt động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($reviews as $review)
                            <tr>
                                <td>
                                    {{ ($reviews->currentPage() - 1) * $reviews->perPage() + $loop->iteration }}
                                </td>

                                <td>{{ $review->user->name ?? 'N/A' }}</td>

                                <td>{{ $review->product->name ?? 'N/A' }}</td>

                                <td>
                                    ⭐ {{ $review->rating }}/5
                                </td>

                                <td style="max-width:200px">
                                    {{ $review->comment }}
                                </td>

                                <td>
                                   @if($review->status == 'pending')
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                    @elseif($review->status == 'approved')
                                        <span class="badge bg-success">Đã duyệt</span>
                                    @else
                                        <span class="badge bg-danger">Đã từ chối</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="list-icon-function">

                                        {{-- DUYỆT --}}
                                        @if($review->status == 'pending')
                                        <form method="POST" action="{{ route('admin.review.status', $review->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <button name="status" value="approved" class="btn btn-success btn-sm">
                                                    ✔ Duyệt
                                                </button>

                                                <button name="status" value="rejected" class="btn btn-danger btn-sm">
                                                    ✖ Từ chối
                                                </button>
                                            </form>
                                        @endif

                                        {{-- XOÁ --}}
                                        <form action="{{route('admin.review.delete',$review->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>

            <div class="divider"></div>

            {{-- PAGINATION --}}
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $(function() {
        $('.delete').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            swal({
                title: "Bạn Chắc Chắn ?",
                text: "Muốn Xóa Không?",
                type: "Cảnh Báo",
                buttons: ["Không", "Có"],
                confirmButtonColor: '#dc3545'
            }).then(function(result) {
                if (result) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush