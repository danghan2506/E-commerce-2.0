<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    /*
        Thuộc tính này xác định các trường mà bạn có thể "điền" một cách an toàn 
        thông qua các thao tác tạo (create) hoặc cập nhật (update) đối tượng trong cơ sở dữ liệu.
    */

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount'
    ];
    // Mỗi OrderItem thuộc về 1 Order::class
    public function order(){
        return $this->belongsTo(Order::class);
    }
    // Mỗi orderItem thuộc về 1 Product::class
    public function product(){
        return $this ->belongsTo(Product::class);
    }
}
