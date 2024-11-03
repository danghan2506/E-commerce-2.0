<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Product')]
class ProductsPage extends Component
{
    use LivewireAlert;
    use WithPagination;
    #[Url]
    public $selected_categories = [];
    #[Url]
    public $selected_brands = [];
    #[Url]
    public $featured;
    #[Url]
    public $on_sale;
    public $price_range =3000000;
    #[Url]
    public $sort = 'latest';
    // Them san pham vao gio hang
    public function addToCart($product_id){
       $total_count = CartManagement::addItemToCart($product_id);
    //    Gui tong so luong san pham den navbar de cap nhat thong tin ve gio hang o giao dien
       $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
       $this->alert('success', 'Product added to the cart successfully!',[
        'position' => 'bottom-end',
        'time' => 3000,
        'toast' => true,
       ]);
    }
    public function render()
    {
        $productsQuery = Product::query()->where('is_active', 1);
       
        // lọc danh sách sản phẩm để chỉ hiển thị những sản phẩm thuộc các danh mục đó.
        if(!empty($this->selected_categories)) {
            $productsQuery->whereIn('category_id', $this->selected_categories);
        }
        if(!empty($this->selected_brands)) {
            $productsQuery->whereIn('brand_id', $this->selected_brands);
        }
        if($this->featured){
            $productsQuery->where('is_featured', 1);
        }
        if($this->on_sale){
            $productsQuery->where('on_sale', 1);
        }
        // Lọc sản phẩm trong giá trị từ 0 cho đến giá trị người dùng chọn
        if($this->price_range){
            $productsQuery->whereBetween('price', [0, $this->price_range]);
        }
        if($this->sort =='latest'){
            $productsQuery->latest();
        }
        if($this->sort == 'price'){
            $productsQuery->orderBy('price');
        }
        return view('livewire.products-page' ,[
            // 1 trang sẽ bao gồm 6 sản phẩm
            'products' => $productsQuery->paginate(9),
            'brands' =>Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' =>Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
