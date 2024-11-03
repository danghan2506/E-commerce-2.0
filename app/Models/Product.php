<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'description',
        'price',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale'
    ];
    protected $casts = [
        'images' => 'array',
    ];
    // Lấy thông tin sản phẩm(Product) thuộc Category::class
    public function category(){
        return $this ->belongsTo(Category::class);
    }
    // Lấy thông tin sản phẩm thuộc Brand::class
    public function brand(){
        return $this ->belongsTo(Brand::class);
    }
    // Lấy tất cả mục đặt hàng liên quan đến sản phẩm đó
    public function orderItem(){
        return $this ->hasMany(OrderItem::class);
    }
}
