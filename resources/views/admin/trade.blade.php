@extends('layouts.admin')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .pos-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 60px);
            background: #f4f6f9;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #1e293b;
            padding: 16px;
            box-sizing: border-box;
        }

        .pos-main {
            display: flex;
            flex: 1;
            gap: 16px;
            overflow: hidden;
            width: 100%;
        }

        .pos-left {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .order-table-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .table-pos {
            width: 100%;
            border-collapse: collapse;
        }

        .table-pos thead th {
            background: #ffffff;
            border-bottom: 2px solid #f1f5f9;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #94a3b8;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table-pos tbody tr {
            border-bottom: 1px solid #f8fafc;
            transition: all 0.2s ease;
        }

        .table-pos tbody tr:hover {
            background: #f8fafc;
            border-radius: 8px;
        }

        .table-pos tbody td {
            padding: 16px;
            vertical-align: middle;
            color: #334155;
            font-weight: 500;
        }

        .order-inf-box {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px;
        }

        .pos-summary {
            width: 100%;
            max-width: 500px;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pos-sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pos-sum-row .lbl {
            font-size: 14px;
            color: #64748b;
        }

        .pos-sum-row .val {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
        }

        .pos-sum-input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 16px;
            width: 200px;
            text-align: right;
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            outline: none;
            background: #ffffff;
            transition: all 0.2s ease;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        }

        .pos-sum-input:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        }

        .must-pay-row {
            background: #e0f2fe;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 4px 0;
        }

        .must-pay-row .lbl {
            color: #0369a1;
            font-weight: 600;
        }

        .must-pay-row .val-important {
            font-size: 20px;
            font-weight: 800;
            color: #0369a1;
        }

        .total-row {
            padding-top: 12px;
            border-top: 2px dashed #e2e8f0;
        }

        .total-row .lbl {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .total-row .val-blue {
            font-size: 22px;
            font-weight: 800;
            color: #10b981;
        }

        .pos-actions {
            display: flex;
            gap: 12px;
            padding: 16px 24px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            justify-content: flex-end;
        }

        .btn-complete {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
            transition: all 0.2s ease;
        }

        .btn-complete:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -1px rgba(16, 185, 129, 0.3);
        }

        .btn-ignore {
            background: #ffffff;
            color: #64748b;
            border: 1px solid #cbd5e1;
            padding: 14px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-ignore:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        .pos-right {
            width: 400px;
            min-width: 400px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .pos-tabs {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 4px 12px 0 12px;
        }

        .pos-tab {
            padding: 14px 16px;
            font-size: 14px;
            color: #64748b;
            font-weight: 700;
            position: relative;
        }

        .pos-tab::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 3px;
            background: #0ea5e9;
            border-radius: 3px;
        }

        .information-customers {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #ffffff;
            border-bottom: 1px dashed #e2e8f0;
        }

        .cust-input {
            width: 100%;
            font-size: 15px;
            font-weight: 500;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .cust-input:focus {
            background: #ffffff;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
        }

        .pos-field-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow-y: auto;
            flex: 1;
        }

        .pos-field-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .field-control input,
        .field-control select {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            background: #ffffff;
            box-sizing: border-box;
            outline: none;
            transition: all 0.2s ease;
        }

        .field-control input:focus,
        .field-control select:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
        }
    </style>

    <div class="pos-wrapper">
        <div class="pos-main">

            <div class="pos-left">
                <div class="order-table-wrapper">
                    <table class="table-pos" id="order-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th style="width: 15%">Số lượng</th>
                                <th style="width: 15%">Size</th>
                                <th style="width: 20%">Đơn giá</th>
                                <th style="width: 20%">Thành tiền</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody id="order-table-body">
                        </tbody>
                    </table>
                </div>

                <div class="order-inf-box">
                    <div class="pos-summary">
                        <div class="pos-sum-row">
                            <span class="lbl">Tổng tiền hàng</span>
                            <span class="val" id="total-amount">0 đ</span>
                        </div>
                        <div class="pos-sum-row">
                            <span class="lbl">Mã ưu đãi (F4)</span>
                            <input type="text" class="pos-sum-input" id="coupon-code" placeholder="Nhập mã..." />
                        </div>
                        <div class="pos-sum-row">
                            <span class="lbl">Chiết khấu giảm giá</span>
                            <span class="val" id="discount-amount" style="color: #ef4444;">0 đ</span>
                        </div>
                        <div class="pos-sum-row must-pay-row">
                            <span class="lbl">Khách cần thanh toán</span>
                            <span class="val-important" id="customer-must-pay">0 đ</span>
                        </div>
                        <div class="pos-sum-row">
                            <span class="lbl">Tiền mặt khách đưa</span>
                            <input type="number" class="pos-sum-input" id="customer-paid" placeholder="0" />
                        </div>
                        <div class="pos-sum-row total-row">
                            <span class="lbl">Tiền thừa hoàn trả:</span>
                            <span class="val-blue" id="change-amount">0 đ</span>
                        </div>
                    </div>
                </div>

                <div class="pos-actions">
                    <button class="btn-ignore">Hủy lệnh (Bỏ qua)</button>
                    <button class="btn-complete">XÁC NHẬN HOÀN TẤT</button>
                </div>
            </div>

            <div class="pos-right">
                <div class="pos-tabs">
                    <div class="pos-tab">THÔNG TIN GIAO DỊCH</div>
                </div>
                
                <div class="information-customers">
                    <input type="text" class="cust-input" id="customer-phone" placeholder="Tìm SĐT khách hàng (F3)" />
                    <input type="text" class="cust-input" id="customer-name" placeholder="Họ tên khách hàng" />
                </div>

                <div class="pos-field-container">
                    <div class="pos-field-row">
                        <span class="field-label">Ngày sinh nhật</span>
                        <div class="field-control"><input type="date" id="dob" /></div>
                    </div>
                    <div class="pos-field-row">
                        <span class="field-label">Nhân viên phụ trách bán</span>
                        <div class="field-control">
                            <select id="salesperson">
                                <option value="">-- Chọn nhân viên --</option>
                                <option value="Lê Văn Dương">Lê Văn Dương</option>
                                <option value="Lê Tấn An">Lê Tấn An</option>
                                @isset($salespersons)
                                    @foreach($salespersons as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                    <div class="pos-field-row">
                        <span class="field-label">Phương thức thanh toán</span>
                        <div class="field-control">
                            <select id="payment-method">
                                <option value="tien_mat">Tiền mặt</option>
                                <option value="chuyen_khoan">Chuyển khoản QR</option>
                                <option value="the">Quẹt thẻ POS</option>
                                <option value="vnpay">Ví VNPAY</option>
                            </select>
                        </div>
                    </div>
                    <div class="pos-field-row">
                        <span class="field-label">Kênh bán hàng</span>
                        <div class="field-control">
                            <select id="sell-from">
                                <option value="">-- Lựa chọn --</option>
                                <option value="counter">Bán tại quầy</option>
                                <option value="online">Đơn từ Online</option>
                            </select>
                        </div>
                    </div>
                    <div class="pos-field-row">
                        <span class="field-label">Nguồn khách hàng</span>
                        <div class="field-control"><input type="text" id="source-pp" placeholder="Ví dụ: Facebook, Chợ đêm..." /></div>
                    </div>
                    <div class="pos-field-row">
                        <span class="field-label">Chiến dịch Marketing</span>
                        <div class="field-control"><input type="text" id="campaign-pp" placeholder="Tên sự kiện ưu đãi..." /></div>
                    </div>
                    <div class="pos-field-row">
                        <span class="field-label">Mã vận đơn đối tác Ship</span>
                        <div class="field-control"><input type="text" id="ship-code-pp" placeholder="Mã GHTK, GHN..." /></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    @stack("scripts")
@endsection