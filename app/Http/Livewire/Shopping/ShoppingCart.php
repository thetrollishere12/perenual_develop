<?php

namespace App\Http\Livewire\Shopping;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\CouponPromo;
use Auth;
use WireUi\Traits\Actions;
class ShoppingCart extends Component
{
    use Actions;
    public $cid;
    public $pid;
    public $quantity;
    public $promoCode;
    public $error;

    public function applyPromo(){

        $promoCode = $this->promoCode;

        $this->reset('promoCode');

        $coupon = CouponPromo::where('coupon_code',$promoCode);

        if (count($coupon->get()) > 0) {

            // Belongs to another user
            if ($coupon->value('coupon_to_user')) {
                if ($coupon->value('coupon_to_user') != Auth::id()) {

                    return $this->addError('error', 'Invalid Code To User');
                }
            }
            // Expirary Date
            if ($coupon->value('redeemed_by')) {
                if(Carbon::createFromFormat('Y-m-d H:i:s',$coupon->value('redeemed_by'))->isPast()){
                    return $this->addError('error', 'Promo Code Expired');
                }
            }
            // Already Applied
            if (session('cart.coupon_code_applied.coupon_code') == $promoCode) {
                return $this->addError('error', 'Promo Already Applied');
            }

            apply_promo($coupon->value('coupon_code'));

            return $this->addError('success', 'Promo Code Applied');

        }else{
            return $this->addError('error', 'Invalid Promo Code');
        }

        return $this->addError('error', 'Promo Code Error');

    }

    public  function removePromo(){
        session()->forget('cart.coupon_code_applied');
        return response()->json(['status'=>'valid','message'=>'Promo Code Removed'],200);
    }

    public function increment($cid,$pid){

        $cart = session()->get('cart.shopping_cart');

        if($cart) {

            if(isset($cart[$cid])) {

                $increment = $cart[$cid]['list'][$pid]['purchased_quantity']+1;

                if ($increment <= $cart[$cid]['list'][$pid]['product']->quantity) {
                    $cart[$cid]['list'][$pid]['purchased_quantity'] = $increment;
                }else{
                    $cart[$cid]['list'][$pid]['purchased_quantity'] = $cart[$cid]['list'][$pid]['product']->quantity;
                }

                session()->put('cart.shopping_cart', $cart);

            }

        }else{

            return $this->addError('error', 'Please Contact (Error 1001)');

        }

    }

    public function decrement($cid,$pid){

        $cart = session()->get('cart.shopping_cart');

        if($cart) {

            if(isset($cart[$cid])) {

                $increment = $cart[$cid]['list'][$pid]['purchased_quantity']-1;

                if ($increment == 0) {
                    $cart[$cid]['list'][$pid]['purchased_quantity'] = 1;
                }else{
                    $cart[$cid]['list'][$pid]['purchased_quantity'] = $increment;
                }

                session()->put('cart.shopping_cart', $cart);

            }

        }else{

            return $this->addError('error', 'Please Contact (Error 1002)');

        }

    }

    public function delete($cid,$pid){
 
        $cart = session()->get('cart.shopping_cart');
        
        if(isset($cart[$cid]['list'])) {

            if (count($cart[$cid]['list']) > 1) {

                session()->forget('cart.shopping_cart.'.$cid.'.list.'.$pid);

            }else{

                session()->forget('cart.shopping_cart.'.$cid);

            }

            $this->notification([
                'title'       => 'Removed From Cart',
                'description' => 'Product successfull removed from cart',
                'icon'        => 'x-circle',
                'iconColor'   => 'text-negative-400'
            ]);


        }

    }

    public function render()
    {

        cart_media_ownership();

        revise_cart();

        if (session('cart.coupon_code_applied')) {   
            apply_promo(session('cart.coupon_code_applied.coupon_code'));
        }

        $total = subtotal();

        return view('livewire.shopping.shopping-cart',["total"=>$total]);

    }
}
