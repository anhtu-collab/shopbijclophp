@extends('layouts.admin')

@section('content')
<style>
    .invoice-master {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
        padding: 40px 15px;
        min-height: 100vh;
    }

    .invoice-card {
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Top Bar trang trí */
    .invoice-top-accent {
        height: 8px;
        background: linear-gradient(90deg, #4f46e5, #818cf8);
    }

    .invoice-body {
        padding: 50px;
    }

    /* Header */
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
    }

    .brand-logo h2 {
        font-weight: 800;
        font-size: 28px;
        color: #1e1b4b;
        margin: 0;
        letter-spacing: -1px;
    }

    .invoice-status {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        background: #ecfdf5;
        color: #059669; /* Xanh lá cho Paid */
        border: 1px solid #d1fae5;
    }

    /* Grid thông tin */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-label {
        font-size: 12px;
        text-transform: uppercase;
        color: #9ca3af;
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 15px;
        color: #1f2937;
        font-weight: 500;
        line-height: 1.5;
    }

    /* Bảng sản phẩm */
    .table-container {
        margin-bottom: 30px;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        background: #f9fafb;
        padding: 12px 15px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        color: #6b7280;
        border-bottom: 2px solid #f3f4f6;
    }

    .modern-table td {
        padding: 20px 15px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 15px;
    }

    .text-right { text-align: right !important; }

    /* Phần tính tiền */
    .summary-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .summary-box {
        width: 300px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 15px;
        color: #4b5563;
    }

    .summary-row.total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #f3f4f6;
        color: #111827;
        font-weight: 700;
        font-size: 20px;
    }

    /* Footer */
    .invoice-footer {
        background: #f9fafb;
        padding: 30px 50px;
        text-align: center;
        border-top: 1px solid #f3f4f6;
    }

    .footer-note {
        font-size: 13px;
        color: #9ca3af;
    }

    .no-print {
    max-width: 900px; 
    margin: 20px auto;
    text-align: center; 
}

.btn-print {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 15px;               
    padding: 18px 45px;       
    background-color: #ffffff; 
    color: #4f46e5;           
    border: 2px solid #4f46e5;  
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
}

.btn-print span.icon {
    font-size: 28px; 
}


.btn-print:hover {
    background-color: #4f46e5;
    color: white;
    transform: scale(1.05); /* Nút to nhẹ lên khi rà chuột vào */
    box-shadow: 0 15px 30px rgba(79, 70, 229, 0.4);
}

</style>

<main class="invoice-master">
    <div class="invoice-card">
        <div class="invoice-top-accent"></div>
        
        <div class="invoice-body">
            {{-- Header --}}
            <div class="invoice-header">
                <div class="brand-logo">
                    <h2>BRIJCLO<span style="color: #4f46e5;">.</span></h2>
                    <p style="font-size: 14px; color: #6b7280; margin-top: 5px;">Mã đơn hàng: #{{ $order->id }}</p>
                </div>
                <div class="text-right">
                    <span class="invoice-status">Đã thanh toán</span>
                    <p style="margin-top: 10px; font-size: 14px; color: #6b7280;">
                        Ngày lập: {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="info-grid">
                <div>
                    <span class="info-label">Gửi từ</span>
                    <div class="info-value">
                        <strong >Brijclo Store</strong>
                        <br>
                        NGÕ 122 PHỐ MAI DỊCH, CẦU GIẤY<br>
                        TP.HÀ NỘI<br>
                        0912 345 678
                    </div>
                </div>
                <div>
                    <span class="info-label">Khách hàng</span>
                    <div class="info-value">
                        <strong>{{ $order->name }}</strong><br>
                        {{ $order->address }}<br>
                        {{ $order->city }}<br>
                        {{ $order->phone ?? '' }}
                    </div>
                </div>
                <div>
                    <span class="info-label">Thanh toán</span>
                    <div class="info-value">
                        Phương thức: {{ $order->transaction?->mode ?? 'Chuyển khoản' }}<br>
                        Trạng thái: Hoàn tất<br>
                        Thời hạn: {{ \Carbon\Carbon::parse($order->created_at)->addDays(7)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- Bảng hàng hóa --}}
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-right">Số lượng</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-right">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                           <td>
                            <div style="font-weight: 600; color: #111827; margin-bottom: 4px;">
                                {{ $item->product->name }}
                            </div>

                            <div style="font-size: 12px; color: #9ca3af; line-height: 1.6;">
                                <div>MÃ SP: {{ $item->product->SKU }}</div>

                                @php
                                    $options = $item->options;
                                @endphp

                                @if(!empty($options))
                                    <div>Size: {{ $options['size'] ?? '-' }}</div>
                                    <div>Màu: {{ $options['color'] ?? '-' }}</div>
                                @endif
                            </div>
                        </td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td class="text-right" style="font-weight: 600;">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tổng kết tiền --}}
            <div class="summary-wrapper">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span>{{ number_format($order->subtotal, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="summary-row" style="color: #ef4444;">
                        <span>Giảm giá</span>
                        <span>- {{ number_format($order->discount, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Thuế (VAT 10%)</span>
                        <span>{{ number_format($order->tax, 0, ',', '.') }} đ</span>
                    </div>
                    <div class="summary-row total">
                        <span>Tổng cộng</span>
                        <span style="color: #4f46e5;">{{ number_format($order->total, 0, ',', '.') }} đ</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <p style="font-weight: 600; color: #374151; margin-bottom: 5px;">Cảm ơn bạn đã tin tưởng Brijclo!</p>
            <p class="footer-note">Nếu có bất kỳ thắc mắc nào về hoá đơn, vui lòng liên hệ hotro@brijclo.vn</p>
        </div>
    </div>
    <div class="no-print">
    <button onclick="window.print()" class="btn-print">
        <span class="icon">🖨️</span>
        <span class="text">IN HOÁ ĐƠN</span>
    </button>
</div>
</main>
@endsection