<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // Tương ứng với các trường trong migration 
    protected $fillable = ['name', 'slug', 'image', 'is_active'];
    
    // Truy xuất tất cả các sản phẩm liên quan đến mô hình hiện tại.
    // hasMany là một phương thức được cung cấp bởi Eloquent ORM của Laravel, 
    //cho phép bạn định nghĩa một mối quan hệ "một-nhiều".
    // Ý nghĩa: mỗi đối tượng của mô hình hiện tại có thể có nhiều sản phấm
    public function products(){
        return $this->hasMany(Product::class);
    }
}
