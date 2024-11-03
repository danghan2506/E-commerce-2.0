<?php

namespace App\Livewire;

use App\Models\Order;
use Stripe\Checkout\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Stripe;
#[Title('Success')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;
    public function render()
    {
        // chứa đơn hàng mới nhất của người dùng hiện tại, kèm theo địa chỉ liên quan đến đơn hàng đó.
        $latest_order = Order::with('address')->where('user_id', auth()
        ->user()->id)->latest()->first();
        if ($this->session_id){
            Stripe::setApiKey(env('STRIPE_API_KEY'));
            $session_info = Session::retrieve($this->session_id);
            if($session_info->payment_status != 'paid'){
                $latest_order->payment_status = 'failed';
                $latest_order->save();
                return redirect()->route('cancel');
            }
            else if($session_info->payment_status == 'paid'){
                $latest_order->payment_status = 'paid';
                $latest_order->save();
            }
        }
        return view('livewire.success-page',[
            'order' => $latest_order,
        ]);
    }
}
