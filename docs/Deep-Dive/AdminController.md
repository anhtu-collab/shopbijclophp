# Deep Dive: AdminController

## Overview

The `AdminController` handles all admin panel operations including dashboard analytics, product management, order management, brand management, category management, coupon management, slide management, and contact management.

## Responsibilities

- Dashboard data aggregation (sales, orders, monthly statistics)
- CRUD operations for brands, categories, products, coupons, slides
- Order management and status updates
- Contact message management
- Image processing for uploads
- Search functionality

## Key Files

- **`app/Http/Controllers/AdminController.php`** - Main admin controller
- **`app/Models/Brand.php`** - Brand entity
- **`app/Models/Category.php`** - Category entity
- **`app/Models/Product.php`** - Product entity
- **`app/Models/Order.php`** - Order entity

## Dashboard Implementation

```php
public function index()
{
    $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);
    $dashboardDatas = DB::select("Select sum(total) AS TotalAmount, ...");

    $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
        IFNULL(D.TotalAmount,0) As TotalAmount ... FROM month_names M
        LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName, ...) D ...");
}
```

Uses raw SQL queries for complex aggregations and LEFT JOIN for monthly statistics across all 12 months.

## Image Processing

```php
private function GenerateBrandThumbailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/brands');
    $img = Image::read($image->path());
    $img->cover(124, 124, "top");
    $img->resize(124, 124, function($constraint) {
        $constraint->aspectRatio();
    });
    $img->save($destinationPath . '/' . $imageName);
}
```

Uses Intervention Image library for thumbnail generation with fixed 124x124 dimensions.

## Admin Routes Structure

| Route | Method | Purpose |
|-------|--------|---------|
| `/admin` | GET | Dashboard view |
| `/admin/brands` | GET | Brand listing |
| `/admin/brand/add` | GET | Add brand form |
| `/admin/brand/store` | POST | Create brand |
| `/admin/brand/edit/{id}` | GET | Edit brand form |
| `/admin/brand/update` | PUT | Update brand |
| `/admin/brand/{id}/delete` | DELETE | Delete brand |
| `/admin/categories` | GET | Category listing |
| `/admin/products` | GET | Product listing |
| `/admin/orders` | GET | Order listing |

## Dependencies

- **Internal**: Brand, Category, Product, Order, Coupon, Slide, Contact models
- **External**: Intervention Image, Carbon, Str, File utilities