@extends('layouts.admin')
@section('content')
<style>
    td.link-col {
    max-width: 250px;         
    white-space: normal;      
    word-break: break-all;      
    overflow-wrap: break-word;  
}
    </style>
<div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Tất Cả Người Dùng</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Bảng Điều Khiển</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny"> Tất Cả Người Dùng</div>
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
                                        <a class="tf-button style-1 w208" href="{{route('admin.users.add')}}"><i
                                                class="icon-plus"></i>Thêm</a>
                                    </div>


                                    <div class="wg-table table-all-user">
                                         @if(Session::has('status')) 
                                            <p class="alert alert-success">{{Session::get('status')}}</p>
                                        @endif 
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Tên</th>
                                                    <th>Email</th>
                                                    <th>Số điện thoại</th>
                                                    {{-- <th>Mật khẩu</th> --}}
                                                    <th>Chức năng</th>
                                                    <th>Hoạt Động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $user)
                                                <tr>
                                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                                    <td class="pname">
                                                        {{ $user->name }}
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->mobile }}</td>

                                                    {{-- <td>{{ $user->password }}</td> --}}
                                                    
                                                    <td class="link-col">{{ $user->utype }}</td>
                                                    <td>

                                                        <div class="list-icon-function">
                                                            <a href="{{ route('admin.users.detail', ['id' => $user->id]) }}">
                                                                    <div class="item eye">
                                                                        <i class="icon-eye"></i>
                                                                    </div>
                                                                </a>
        
                                                            <a href="{{route('admin.users.edit', ['id' => $user->id])}}">
                                                                <div class="item edit">
                                                                    <i class="icon-edit-3"></i>
                                                                </div>
                                                            </a>
                                                            <form action="{{route('admin.users.delete' , ['id' => $user->id])}}" method="POST">
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
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                        {{-- {{ $user->links('pagination::bootstrap-5') }}  --}}

                                 </div>
                                </div>
                            </div>
                        </div>
                        

@endsection
 @push('scripts')
    <script>
        $(function(){
            $('.delete').on('click',function(e){
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Bạn Chắc chắn?",
                    text: "Muốn Xóa Không?",
                    type:"Cảnh Báo",
                    buttons:["Không","Có"],
                    comfirmButtonColor:'#3545',

                }).then(function(result){
                    if(result){
                        form.submit();
                    }
                })
            });

        });
    </script>
@endpush