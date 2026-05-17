<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
        if (Schema::hasColumn('products', 'quantity')) {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }

}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->integer('quantity')->default(0);
    });
}
};
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
    if (!Schema::hasColumn('addresses', 'is_default')) {
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('is_default')->default(0);
        });
    }
}

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('is_default')->default(0);
        });
    }

};
// / <?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('blogs', function (Blueprint $table) {
//             $table->id();
//             $table->timestamps();
//         });

        
//     Schema::create('blogs', function (Blueprint $table) {
//         $table->id();
//         $table->string('title');
//         $table->string('slug')->unique();
//         $table->string('thumbnail')->nullable();
//         $table->text('excerpt')->nullable();        // Mô tả ngắn
//         $table->longText('content');               // Nội dung chính
//         $table->string('category')->nullable();    // Danh mục: sản phẩm, thương hiệu, chất liệu...
//         $table->string('author')->nullable();
//         $table->tinyInteger('status')->default(1); // 1: active, 0: inactive
//         $table->timestamps();
//     });
//     }


//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('blogs');
//     }
// };
