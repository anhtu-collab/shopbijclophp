<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now();

        $categories = [
            ['name' => 'Áo Khoác', 'slug' => 'ao-khoac', 'image' => 'category-ao-khoac.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Áo Thun', 'slug' => 'ao-thun', 'image' => 'category-ao-thun.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quần Jeans', 'slug' => 'quan-jeans', 'image' => 'category-quan-jeans.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Giày Dép', 'slug' => 'giay-dep', 'image' => 'category-giay-dep.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Phụ Kiện', 'slug' => 'phu-kien', 'image' => 'category-phu-kien.jpg', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('categories')->upsert($categories, ['slug'], ['name', 'image', 'updated_at']);

        $brands = [
            ['name' => 'Brijclo', 'slug' => 'brijclo', 'image' => 'brand-brijclo.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Modern Wear', 'slug' => 'modern-wear', 'image' => 'brand-modern-wear.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Street One', 'slug' => 'street-one', 'image' => 'brand-street-one.jpg', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('brands')->upsert($brands, ['slug'], ['name', 'image', 'updated_at']);

        $colors = [
            ['name' => 'Đen', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Trắng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Xanh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đỏ', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('colors')->upsert($colors, ['name'], ['updated_at']);

        $sizes = [
            ['name' => 'S', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'M', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'L', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'XL', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('sizes')->upsert($sizes, ['name'], ['updated_at']);

        $slides = [
            [
                'tagline' => 'Xu hướng mới',
                'title' => 'BST Thu Đông 2026',
                'subtitle' => 'Phong cách cá tính cho mọi ngày',
                'link' => route('shop.index'),
                'image' => 'slide-1.jpg',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tagline' => 'Sale Hấp Dẫn',
                'title' => 'Giảm giá đến 50%',
                'subtitle' => 'Mua sắm phong cách, tiết kiệm ngân sách',
                'link' => route('shop.index'),
                'image' => 'slide-2.jpg',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tagline' => 'Sản phẩm mới',
                'title' => 'Đón đầu xu hướng',
                'subtitle' => 'Chọn lựa cho phong cách thường nhật',
                'link' => route('shop.index'),
                'image' => 'slide-3.jpg',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('slides')->upsert($slides, ['image'], ['tagline', 'title', 'subtitle', 'link', 'status', 'updated_at']);

        $categoryIds = DB::table('categories')->pluck('id', 'slug')->all();
        $brandIds = DB::table('brands')->pluck('id', 'slug')->all();

        $products = [
            [
                'name' => 'Áo Khoác Nỉ Nam',
                'slug' => 'ao-khoac-ni-nam',
                'short_description' => 'Áo khoác nỉ nam ấm áp, phong cách đường phố.',
                'description' => 'Áo khoác nỉ cao cấp, phù hợp mặc đi học, đi làm hoặc dạo phố. Chất liệu mềm mại, giữ ấm tốt.',
                'regular_price' => 750000,
                'sale_price' => 599000,
                'SKU' => 'BRJ-AK-001',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 25,
                'image' => 'product-ao-khoac-ni-nam.jpg',
                'images' => json_encode(['product-ao-khoac-ni-nam-2.jpg', 'product-ao-khoac-ni-nam-3.jpg']),
                'category_id' => $categoryIds['ao-khoac'] ?? null,
                'brand_id' => $brandIds['brijclo'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Áo Thun Form Rộng',
                'slug' => 'ao-thun-form-rong',
                'short_description' => 'Áo thun unisex, thoáng mát, dễ phối đồ.',
                'description' => 'Áo thun form rộng với chất vải cotton cao cấp. Thoáng mát và không xù sau nhiều lần giặt.',
                'regular_price' => 350000,
                'sale_price' => 279000,
                'SKU' => 'BRJ-AT-002',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 40,
                'image' => 'product-ao-thun-form-rong.jpg',
                'images' => json_encode(['product-ao-thun-form-rong-2.jpg']),
                'category_id' => $categoryIds['ao-thun'] ?? null,
                'brand_id' => $brandIds['modern-wear'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Quần Jeans Rách',
                'slug' => 'quan-jeans-rach',
                'short_description' => 'Quần jeans rách cá tính, phù hợp nhiều phong cách.',
                'description' => 'Quần jeans form slim fit, chất liệu denim co giãn nhẹ. Thiết kế rách ở đầu gối tạo điểm nhấn.',
                'regular_price' => 620000,
                'sale_price' => null,
                'SKU' => 'BRJ-QJ-003',
                'stock_status' => 'instock',
                'featured' => true,
                'quantity' => 30,
                'image' => 'product-quan-jeans-rach.jpg',
                'images' => json_encode(['product-quan-jeans-rach-2.jpg']),
                'category_id' => $categoryIds['quan-jeans'] ?? null,
                'brand_id' => $brandIds['street-one'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Giày Sneaker Unisex',
                'slug' => 'giay-sneaker-unisex',
                'short_description' => 'Giày sneaker thời trang, mang lại cảm giác êm ái.',
                'description' => 'Giày sneaker unisex, đế cao su chống trượt, thiết kế hiện đại phù hợp cả nam và nữ.',
                'regular_price' => 980000,
                'sale_price' => 849000,
                'SKU' => 'BRJ-GS-004',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 18,
                'image' => 'product-giay-sneaker-unisex.jpg',
                'images' => json_encode(['product-giay-sneaker-unisex-2.jpg']),
                'category_id' => $categoryIds['giay-dep'] ?? null,
                'brand_id' => $brandIds['brijclo'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Balo Thời Trang',
                'slug' => 'balo-thoi-trang',
                'short_description' => 'Balo nhỏ gọn, tiện dụng cho đi làm và đi học.',
                'description' => 'Balo thời trang với nhiều ngăn, chất liệu chống thấm nước và quai đệm êm.',
                'regular_price' => 420000,
                'sale_price' => 359000,
                'SKU' => 'BRJ-BL-005',
                'stock_status' => 'instock',
                'featured' => false,
                'quantity' => 20,
                'image' => 'product-balo-thoi-trang.jpg',
                'images' => json_encode(['product-balo-thoi-trang-2.jpg']),
                'category_id' => $categoryIds['phu-kien'] ?? null,
                'brand_id' => $brandIds['modern-wear'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('products')->upsert($products, ['slug'], ['name', 'short_description', 'description', 'regular_price', 'sale_price', 'SKU', 'stock_status', 'featured', 'quantity', 'image', 'images', 'category_id', 'brand_id', 'updated_at']);

        $sizeIds = DB::table('sizes')->pluck('id', 'name')->all();
        $colorIds = DB::table('colors')->pluck('id', 'name')->all();
        $productIds = DB::table('products')->pluck('id', 'slug')->all();

        $variants = [];

        foreach (['ao-khoac-ni-nam', 'ao-thun-form-rong', 'quan-jeans-rach'] as $slug) {
            if (!isset($productIds[$slug])) {
                continue;
            }

            $productId = $productIds[$slug];
            foreach (['S', 'M', 'L'] as $size) {
                foreach (['Đen', 'Trắng', 'Xanh'] as $color) {
                    $variants[] = [
                        'product_id' => $productId,
                        'size_id' => $sizeIds[$size] ?? null,
                        'color_id' => $colorIds[$color] ?? null,
                        'quantity' => 12,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($variants)) {
            DB::table('product_variants')->upsert($variants, ['product_id', 'size_id', 'color_id'], ['quantity', 'updated_at']);
        }
    }
}
