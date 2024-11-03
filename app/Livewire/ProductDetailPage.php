<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Product Detail')]
class ProductDetailPage extends Component
{   
    public $slug;
    use LivewireAlert;
    public function mount($slug){
        $this->slug = $slug;
    }
    public $quantity = 1;
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);
     //    Gui tong so luong san pham den navbar de cap nhat thong tin ve gio hang o giao dien
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
        $this->alert('success', 'Product added to the cart successfully!',[
         'position' => 'bottom-end',
         'time' => 3000,
         'toast' => true,
        ]);
     }
    public function increaseQty(){
        $this->quantity++;
    }
    public function decreseQty(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }
    public function render()
    {
        return view('livewire.product-detail-page',[
            'product' => Product::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
