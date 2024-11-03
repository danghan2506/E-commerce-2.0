<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'grand_total',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
    ];
    // lấy thông tin đơn hàng(Order) thuộc về User::class
    public function user(){
        return $this->belongsTo(User::class);
    }
    // Đơn hàng(Order) có nhiều sản phẩm(OrderItem)
    public function items(){
        return $this->hasMany(OrderItem::class);
    }
    // Mỗi đơn hàng (Order) chỉ có 1 địa chỉ duy nhất
    public function address(){
        return $this ->hasOne(Address::class);
    }
}
