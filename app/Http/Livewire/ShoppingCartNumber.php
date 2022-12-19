<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShoppingCartNumber extends Component
{

    public $count;

    public function render()
    {
      
        $this->count = 0;

        if (session('cart.shopping_cart')) {
        
            foreach(session('cart.shopping_cart') as $i => $cart){
                $this->count += count($cart['list']);
            }

        }
        
        return view('livewire.shopping-cart-number');
    }
}
