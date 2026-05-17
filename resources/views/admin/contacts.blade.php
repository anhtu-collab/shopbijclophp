@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Tất Cả Bình Luận</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Bảng Điều Khiển </div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Tất Cả Bình Luận</div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="wg-box">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <form class="form-search">
                                                <fieldset class="name">
                                                    <input type="text" placeholder="Tìm Kiếm..." class="" name="name"
                                                        tabindex="2" value="" aria-required="true" required="">
                                                </fieldset>
                                                <div class="button-submit">
                                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                                        <th class="text-center">Số Điện Thoại</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Bình luận</th>
                                                        <th class="text-center">Ngày</th>
                                                        <th class="text-center">Hoạt Động</th>
                                                    </tr>
                                                </thead>
                                               @foreach ($contacts as $contact)
                                               <tr>
                                                  <td>{{ ($contacts->currentPage() - 1) * $contacts->perPage() + $loop->iteration }}</td>
                                                   <td>{{ $contact->name }}</td>
                                                   <td>{{ $contact->phone }}</td>
                                                   <td>{{ $contact->email }}</td>
                                                   <td>{{ $contact->comment}}</td>
                                                   <td>{{ $contact->created_at}}</td>

                                                   <td>
                                                       <div class="list-icon-function">
                                                           <form action="{{ route('admin.contact.delete', ['id' => $contact->id]) }}" method="POST">
                                                               @csrf
                                                               @method('DELETE')
                                                               <div class="item text-danger delete">
                                                                   <i class="icon-trash-2"></i>
                                                               </button>
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
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                        {{ $contacts->links('pagination::bootstrap-5') }}
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