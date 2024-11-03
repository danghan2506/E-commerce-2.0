<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use WithPagination;
#[Title('My Orders')]
class MyOrdersPage extends Component
{

    public function render()
    {
        
        $my_orders = Order::where('user_id', auth()->user()->id)->paginate(2);
        return view('livewire.my-orders-page',[
            'orders' => $my_orders,
        ]);
    }
}
