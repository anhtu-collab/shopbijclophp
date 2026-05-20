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
        $table->dropColumn([
            'shipping_name',
            'shipping_phone',
            'shipping_address',
            'shipping_city',
            'shipping_state',
            'shipping_country',
            'shipping_zip',
            'shipping_locality',
            'shipping_landmark',
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('shipping_name')->nullable();
        $table->string('shipping_phone')->nullable();
        $table->text('shipping_address')->nullable();
        $table->string('shipping_city')->nullable();
        $table->string('shipping_state')->nullable();
        $table->string('shipping_country')->nullable();
        $table->string('shipping_zip')->nullable();
        $table->string('shipping_locality')->nullable();
        $table->string('shipping_landmark')->nullable();
    });
}
};
