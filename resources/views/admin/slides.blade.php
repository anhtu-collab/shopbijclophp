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
                                    <h3>TẤT CẢ ẢNH QUẢNG CÁO</h3>
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
                                            <div class="text-tiny">Tất Cả Ảnh Quảng Cáo</div>
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
                                        <a class="tf-button style-1 w208" href="{{route('admin.slide.add')}}"><i
                                                class="icon-plus"></i>Thêm</a>
                                    </div>
                                    <div class="wg-table table-all-user">
                                         @if(Session::has('status')) 
                                            <p class="alert alert-success">{{Session::get('status')}}</p>
                                        @endif 
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                         <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Hình Ảnh</th>
                                            <th class="text-center">Khẩu Hiệu</th>
                                            <th class="text-center">Tiêu Đề</th>
                                            <th class="text-center">Phụ Đề</th>
                                            <th class="text-center">Link</th>
                                            <th class="text-center">Hoạt Động</th>
                                        </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($slides as $slide)
                                                <tr>
                                                    <td>{{ ($slides->currentPage() - 1) * $slides->perPage() + $loop->iteration }}</td>
                                                    <td class="pname">
                                                        <div class="image">
                                                            <img src="{{ asset('uploads/slides')}}/{{$slide->image}}" alt="" class="{{$slide->title}}">
                                                        </div>
                                                    </td>
                                                    <td>{{ $slide->tagline }}</td>
                                                    <td>{{ $slide->title }}</td>
                                                    <td>{{ $slide->subtitle }}</td>
                                                    <td class="link-col">{{ $slide->link }}</td>
                                                    <td>
                                                        <div class="list-icon-function">
                                                            <a href="{{route('admin.slide.edit', ['id' => $slide->id])}}">
                                                                <div class="item edit">
                                                                    <i class="icon-edit-3"></i>
                                                                </div>
                                                            </a>
                                                            <form action="{{route('admin.slide.delete' , ['id' => $slide->id])}}" method="POST">
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
                                        {{ $slides->links('pagination::bootstrap-5') }}

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