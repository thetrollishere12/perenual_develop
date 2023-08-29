<?php

namespace App\Http\Livewire\Shopping\Shipping;

use Livewire\Component;

class CartList extends Component
{

    public $shipping;

    public function hydrate(){
        // If allows guest checkout
        if(env('ANONYMOUS_SHOPPING') != 'TRUE' && !Auth::user()){
            return redirect()->to('shopping-cart');
        }
        // Check if cart exist and has products
        if(!session('cart.shopping_cart') && empty(session('cart.shopping_cart'))){
            return redirect()->to('shopping-cart');
        }

    }

    public function dehydrate(){
        cart_media_ownership();
        revise_cart();
        
    }

    public function shippingType($cid,$type){
        session()->put('cart.shopping_cart.'.$cid.'.shipping.type',$type);
        $this->emit('address-updated');
        // $this->dispatchBrowserEvent('cart-updated');
    }

    public function render()
    {

        foreach(session('cart')['shopping_cart'] as $id => $shopping_cart){
            $this->shipping[$id]['type'] = $shopping_cart['shipping']['type'];
        }

        $subtotal = subtotal();
        $shipping = shipping();
        $discount = 0;

        if (session('cart.coupon_code_applied.discount')) {
            $discount = session('cart.coupon_code_applied.discount');
        }

        $tax = 0;

        $total = $subtotal-$tax+$shipping-$discount;

        session()->put('cart.discount',$discount);
        session()->put('cart.subtotal',$subtotal);
        session()->put('cart.tax',$tax);
        session()->put('cart.total',$total);

        // Check if everything is eligable for shipping to selected country

        $eligable = product_eligability();

        if ($eligable == false) {
            $this->emit('disable');
        }else{
            $this->resetErrorBag('shipping');
            $this->emit('enable');
        }


        return view('livewire.shopping.shipping.cart-list');
    }
}
