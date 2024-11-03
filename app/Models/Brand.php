<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    // Định nghĩa mối qh 1-nhiều 
    /*
    Ý nghĩa:
    Mối quan hệ này có nghĩa là mỗi đối tượng của mô hình hiện tại (ví dụ: một Brand) 
    có thể có nhiều sản phẩm (các đối tượng của mô hình Product).
    Ví dụ: Nếu mô hình hiện tại là Brand, thì một danh mục có thể chứa nhiều sản phẩm khác nhau.
    */
    protected $fillable = ['name', 'slug', 'image', 'is_active'];
    public function products(){
        return $this->hasMany(Product::class);
    }
}
