<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thay đổi enum status với các trạng thái mới
            $table->dropColumn('status');

            $table->enum('status', [
                'pending',       // Chờ xác nhận
                'confirmed',     // Đã xác nhận
                'processing',    // Đang chuẩn bị hàng
                'shipping',      // Đang giao hàng
                'delivered',     // Đã giao hàng
                'completed',     // Hoàn tất
                'canceled',     // Đã huỷ
                'returned'       // Trả hàng
            ])->default('pending');

            // Thêm các cột ngày cho các trạng thái mới
            $table->date('confirmed_date')->nullable();
            $table->date('processing_date')->nullable();
            $table->date('shipping_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->date('returned_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Quay lại trạng thái cũ
            $table->dropColumn([
                'status',
                'confirmed_date',
                'processing_date',
                'shipping_date',
                'completed_date',
                'returned_date'
            ]);

            $table->enum('status', ['ordered', 'delivered', 'canceled'])->default('ordered');
            $table->date('delivered_date')->nullable();
            $table->date('canceled_date')->nullable();
        });
    }
};