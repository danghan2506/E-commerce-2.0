<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - HT Shop')]
class HomePage extends Component
{
    public function render()
    {   
        // lấy tất cả các thương hiệu (brands) có trạng thái hoạt động (is_active = 1) 
        //từ cơ sở dữ liệu và lưu kết quả vào biến $brands.
        $brands = Brand::where('is_active', 1)->get();
        $categories =Category::where('is_active', 1)->get();
        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories
        ]); 
    }
}
