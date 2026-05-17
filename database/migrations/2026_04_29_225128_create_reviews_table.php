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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();

        // user nào review
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // sản phẩm nào
        $table->foreignId('product_id')->constrained()->onDelete('cascade');

        // số sao
        $table->integer('rating');

        // nội dung đánh giá
        $table->text('comment');

        // trạng thái duyệt
        $table->enum('status', ['pending','approved'])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
