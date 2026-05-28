@extends('layouts.admin')
@section('content')

    <div class="main-content-inner">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Tra Hàng Tồn</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Bảng Điều Khiển</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Tra Hàng Tồn</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">

                <form method="GET" action="{{ route('admin.stock') }}" class="flex items-center gap10 mb-20 flex-wrap">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo tên hoặc mã SKU..."
                        style="width:250px; border:1px solid #ccc; border-radius:4px; padding:6px 10px;">

                    <select name="display" style="border:1px solid #ccc; border-radius:4px; padding:6px 10px;">
                        <option value="table">Kiểu Bảng</option>
                    </select>

                    <button type="submit" class="tf-button style-1"
                        style="background:#4CAF50; color:white; padding:6px 16px; border-radius:4px;">
                        Tìm kiếm
                    </button>

                    @if($search)
                        <a href="{{ route('admin.stock') }}" style="color:#e74c3c; font-size:13px;">✕ Xóa lọc</a>
                    @endif
                </form>

         
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class="table table-bordered"
                        style="font-size:13px; white-space:nowrap; border-collapse:collapse; min-width:800px;">

                        <thead style="background:#f0f0f0;">
                            <tr>
                                <th style="min-width:160px; position:sticky; left:0; background:#f0f0f0; z-index:2;">
                                    Sản phẩm
                                </th>
                                <th class="text-center" style="min-width:80px;">Tổng cộng</th>

                   
                                @foreach($sizes as $size)
                                    <th class="text-center" style="min-width:55px;">
                                        {{ strtoupper($size->name) }}
                                        <div style="font-weight:normal; color:#e74c3c; font-size:12px;">
                                            ▲
                                        </div>
                                    </th>
                                @endforeach

                                <th class="text-center">Tổng cộng</th>
                            </tr>

             
                            <tr style="background:#fff3cd; font-weight:600;">
                                <td style="position:sticky; left:0; background:#fff3cd;"></td>
                                <td class="text-center">{{ $grandTotal }}</td>

                                @foreach($sizes as $size)
                                    <td class="text-center"
                                        style="{{ ($colTotals[$size->id] ?? 0) == 0 ? '' : 'color:#e74c3c;' }}">
                                        {{ $colTotals[$size->id] ?? 0 }}
                                    </td>
                                @endforeach

                                <td class="text-center">{{ $grandTotal }}</td>
                            </tr>
                        </thead>

            
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                           
                                    <td style="position:sticky; left:0; background:#fff; z-index:1;">
                                        <div style="font-weight:600; font-size:12px;">{{ $row['sku'] }}</div>
                                        <div style="display:flex; align-items:center; gap:6px; margin-top:2px;">
                                            @if($row['color_hex'])
                                                <span style="display:inline-block; width:14px; height:14px;
                                                                 background:{{ $row['color_hex'] }};
                                                                 border:1px solid #ccc; border-radius:2px;"></span>
                                            @endif
                                            <span style="font-size:12px; color:#555;">{{ $row['color_name'] }}</span>
                                        </div>
                                    </td>

                                    <td class="text-center" style="font-weight:600;">
                                        {{ $row['total'] }}
                                    </td>

                              
                                    @foreach($sizes as $size)
                                        @php $qty = $row['size_qty'][$size->id] ?? 0; @endphp
                                        <td class="text-center"
                                            style="{{ $qty == 0 ? 'color:#bbb;' : 'color:#2c3e50; font-weight:500;' }}">
                                            {{ $qty }}
                                        </td>
                                    @endforeach

                                   
                                    <td class="text-center" style="font-weight:600;">
                                        {{ $row['total'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($sizes) + 3 }}" class="text-center" style="padding:20px; color:#999;">
                                        Không tìm thấy sản phẩm nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

               
                        <tfoot style="background:#f8f9fa; font-weight:700;">
                            <tr>
                                <td style="position:sticky; left:0; background:#f8f9fa;">Tổng cộng</td>
                                <td class="text-center">{{ $grandTotal }}</td>
                                @foreach($sizes as $size)
                                    <td class="text-center">{{ $colTotals[$size->id] ?? 0 }}</td>
                                @endforeach
                                <td class="text-center">{{ $grandTotal }}</td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection