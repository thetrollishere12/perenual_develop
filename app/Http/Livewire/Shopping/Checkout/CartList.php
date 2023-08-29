<?php

namespace App\Http\Livewire\Shopping\Checkout;

use Livewire\Component;

class CartList extends Component
{
    public function render()
    {
        session()->put('checkoutCart',session('cart'));
        return view('livewire.shopping.checkout.cart-list');
    }
}
