<?php

namespace App\Http\Livewire\Shopping\Checkout;

use Livewire\Component;

class ShippingError extends Component
{

    protected $listeners = ['disable','enable'];
    public function enable(){
        $this->resetErrorBag('shipping');
    }

    public function disable(){
        $this->addError('shipping', 'cannot ship to '.country_code_to_string(session('cart.shipping.address.country')));

    }

    public function render()
    {
        return view('livewire.shopping.checkout.shipping-error');
    }
}
