<?php

namespace App\Http\Livewire\Shopping\Checkout;

use Livewire\Component;

class ShippingAddress extends Component
{

    public function hydrate(){

        // Check if user provided shipping address
        if(!session('cart.shipping.address')){
            return redirect()->to('shipping');
        }
        
    }
    
    public function render()
    {

        $this->hasShipping = has_shipping();

        return view('livewire.shopping.checkout.shipping-address');
    }
}
