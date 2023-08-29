<?php


use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\CouponPromo;
use App\Models\CurrencyRate;

use App\Models\VariationList;

use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;

// Conversion rate number with currency symbol
function conversion($base_currency,$base_number,$currency){

	$rates = CurrencyRate::where('base_currency',$base_currency)->where('foreign_currency',current_currency_code())->value('rate');

	return (($currency ==  true) ? current_currency().number_format(round($base_number*$rates,2),2) : round($base_number*$rates,2));

}

function conversion_product($base_currency,$quantity,$base_number,$currency){

	$rates = CurrencyRate::where('base_currency',$base_currency)->where('foreign_currency',current_currency_code())->value('rate');

	return (($currency ==  true) ? current_currency().number_format($quantity*round($base_number*$rates,2),2) : $quantity*round($base_number*$rates,2));

}

// Eligable
function product_eligability(){

	$eligable = true;

	foreach(session('cart.shopping_cart') as $sku => $cart) {

        foreach($cart['list'] as $list){
            if (isset($list['eligable']) && $list['eligable'] == false) {

                $eligable = false;
                $this->addError('shipping', 'Product '.strtoupper($list['product']->sku).' does not ship to '.country_code_to_string(session('cart.shipping.address.country')));
                break;

            }
        }

    }
	
    return $eligable;
		
}

// Promo Code

function apply_promo($code){

		$redeem = CouponPromo::where('coupon_code',$code)->first();

		$subtotal = 0;
		$discount = 0;

		// Belongs to another user
		if ($redeem->coupon_to_user) {
			if ($redeem->coupon_to_user != Auth::id()) {
				$discount = 0;
				return $discount;
			}
		}

		// Expirary Date
		if ($redeem->redeemed_by) {
			if(Carbon::createFromFormat('Y-m-d H:i:s',$redeem->redeemed_by)->isPast()){
				$discount = 0;
				return $discount;
			}
		}

		foreach (session('cart.shopping_cart') as $key => $cart) {
			// If specific type if not then its everything in cart
			if ($redeem->required_type) {
				
				if (isset($cart[$redeem->required_type])) {
				
					if ($redeem->required_type_name == $cart[$redeem->required_type]) {
						$subtotal += conversion($cart["currency"],$cart['purchased_quantity']*$cart["price"],false);
					}

				}else{
					continue;
				}

			}
			// Everything in cart
			else{
				$subtotal += conversion($cart["currency"],$cart['purchased_quantity']*$cart["price"],false);
			}
		
		}

		if ($redeem->coupon_type === "percent_off") {

        	$percentage = $redeem->percent_off/100;

        	$discount = $subtotal*$percentage;

        }elseif($redeem->coupon_type === "amount_off"){

        	 $discount = conversion(env('CASHIER_CURRENCY'),$redeem->amount_off,false);

        }else{
        	$discount=0;
        }	

		if ($redeem->price_minimum) {

			if ($subtotal < $redeem->price_minimum) {
				$discount = 0;
        	}

		}

		$coupon_code = [
			'coupon_code'=>$redeem->coupon_code,
			'coupon_type'=>$redeem->coupon_type,
			'amount_off'=>conversion(env('CASHIER_CURRENCY'),$redeem->amount_off,false),
			'percent_off'=>$redeem->percent_off,
			'shipping_off'=>$redeem->shipping_off,
			'minimum'=>conversion(env('CASHIER_CURRENCY'),$redeem->price_minimum,false),
			'discount'=>$discount,
			'type'=>$redeem->required_type,
			'type_name'=>$redeem->required_type_name,
			'expiry_date'=>$redeem->redeemed_by,
		];

		session()->put('cart.coupon_code_applied',$coupon_code);

		return $discount;

}

function redeem_promo($code){

	$redeem = CouponPromo::where('coupon_code',$code)->value('max_redemptions');

	switch($redeem){

		case "once":
			CouponPromo::where('coupon_code',$code)->delete();
			return "coupon_deleted";
			break;
		default:
			return "coupon_not_deleted";
			break;

	}

}

// get subtotal for shopping cart
function subtotal(){

    $subtotal = 0;

    if(session('cart.shopping_cart') && !empty(session('cart.shopping_cart'))){

        foreach (session('cart.shopping_cart') as $sku => $cart) {

            foreach ($cart['list'] as $details){

                $subtotal += conversion_product($details['product']['currency'],$details['purchased_quantity'],$details['product']['price'],false);

            }

        }

        return $subtotal;

    }

}

// Prevent users from buying their own listings
function cart_media_ownership(){

    if (!empty(session('cart.shopping_cart'))) {

    	$store = get_store();

        foreach (session('cart.shopping_cart') as $key => $cart) {

            if (count($store) > 0 && $key == $store->first()->id) {

                session()->forget('cart.shopping_cart.'.$key);

            }

        }
    }

}

// Revise and update shopping cart
function revise_cart(){

	if(session('cart.shopping_cart') && !empty(session('cart.shopping_cart'))){

        foreach (session('cart.shopping_cart') as $key => $cart) {

            foreach ($cart['list'] as $id => $detail){

            	// Update product

            	$product = Product::where('id',$detail['product']->id)->first();

            	session()->put('cart.shopping_cart.'.$key.'.list.'.$id.'.product', $product);

            	// Variation

            	if ($detail['product']['variation'] == true) {
            		
            		if (env('VARIATION') == TRUE){

	            		$product = VariationList::where('id',$detail['product']['variation_id'])->first();

	            		if ($product->quantity == 0) {
		            		
		            		if(isset($cart['list'])) {

				                if (count($cart['list']) > 1) {

				                	session()->forget('cart.shopping_cart.'.$key.'.list.'.$id);

				                }else{
				         			
				                	session()->forget('cart.shopping_cart.'.$key);

				                }

				            }

		            	}elseif($product->quantity < $detail['product']['purchased_quantity']){

		            		$detail['purchased_quantity'] = $product->quantity;
		          			session()->put('cart.shopping_cart.'.$key.'.list.'.$id, $detail['product']);

		            	}

		            }

            	}else{

            		// Check if product is out of stock
	            	if ($product->quantity <= 0) {
	            		
	            		if(isset($cart['list'])) {

			                if (count($cart['list']) > 1) {

			                	session()->forget('cart.shopping_cart.'.$key.'.list.'.$id);

			                }else{
			         			
			                	session()->forget('cart.shopping_cart.'.$key);

			                }

			            }

	            	}elseif($product->quantity < $detail['purchased_quantity']){

	          			session()->put('cart.shopping_cart.'.$key.'.list.'.$id.'.purchased_quantity', $product->quantity);

	            	}

            	}
            
            }

        }

	}

}



// Get shipping cost for shopping cart
function shipping(){

    	$shipping_amount = 0;


    	if (session('cart.shipping.address.country_code')) {
	    // Estimate quantity and cost for product
	    foreach (session('cart.shopping_cart') as $sku => $cart) {
	        
	        $quantity = 0;

	        foreach ($cart['list'] as $l => $details){

	        	$type = "domestic";

	            $shipping = ShippingDomestic::where('id',$details['product']->shippingMethod)->whereIn('origin',[session('cart.shipping.address.country_code'),'Everywhere'])->get();

	            $cart['shipping']['method'] = [];

	            if ($shipping->count() == 0) {
	                
	            	$type = "international";

	                $shipping = ShippingInternational::where('shipping_id',$details['product']->shippingMethod)->whereIn('origin',[session('cart.shipping.address.country_code'),'Everywhere'])->get()->sortBy('cost');

	                if ($shipping->count() == 0) {
	                    
	                    $cart['list'][$l]['eligable'] = false;
	                    continue;

	                }
	            }


	            $shipping = $shipping->first();

	            $cart['list'][$l]['eligable'] = true;

	            $cart['list'][$l]['shipping'] = [
	            	"id"=>$details['product']->shippingMethod,
	            	"type"=>$type,
	            	"quantity"=>$details['purchased_quantity'],
	            	"cost"=>$shipping->cost,
	            	"additional_cost"=>$shipping->additional_cost,
	            	"from"=>$shipping->delivery_from,
	            	"to"=>$shipping->delivery_to,
	            	"currency"=>$details['product']->currency,
	            ];

	        }

	        session()->put('cart.shopping_cart.'.$sku, $cart);

	    }

	  
	    $array = [];
	    // calculate and finalize cost for store from product into array
	    foreach (session('cart.shopping_cart') as $sku => $cart) {

		    foreach ($cart['list'] as $l => $details){

				if (isset($array[$details['shipping']['id']])) {
					$array[$details['shipping']['id']]['purchased_quantity'] = $array[$details['shipping']['id']]['purchased_quantity'] + $details['purchased_quantity'];
				}else{
					$array[$details['shipping']['id']] = [
						'store_id' => $sku,
						'cost' => $details['shipping']['cost'],
						'additional_cost' => $details['shipping']['additional_cost'],
						'currency' => $details['shipping']['currency'],
						'purchased_quantity' => $details['purchased_quantity']+0
					];
				}

		    }

		}
		
		foreach (session('cart.shopping_cart') as $sku => $cart) {

	    	$cart['shipping']['shipping_amount'] = 0;
	    	// finazlize cost for all product with same shipping for shop
	    	foreach ($array as $key => $shipping) {

	    		if ($shipping['store_id'] == $sku) {
				
	    			$cart['shipping']['shipping_amount'] += $shipping['cost']+($shipping['additional_cost']*($shipping['purchased_quantity']-1));

	    		}

			}

			// If user picks delivery
	        $amount = conversion($cart['shipping']['currency'],$cart['shipping']['shipping_amount'],false);

	        $cart['shipping']['convert_shipping_amount'] = $amount;

			if ($cart['shipping']['type'] == 'pickup' && env('LOCAL_PICKUP') == true) {
				// If user picks pickup
	        	$cart['shipping']['convert_amount'] = 0.00;
			}else{

				$cart['shipping']['convert_amount'] = $amount;				
				$shipping_amount += $amount;
			}

			session()->put('cart.shopping_cart.'.$sku, $cart);

		}

	    session()->put('cart.shipping.currency',current_currency_code());
	    session()->put('cart.shipping.shipping_amount',$shipping_amount);
		
	}

	return $shipping_amount;

}

function has_shipping(){

	$hasShipping = false;

    foreach (session('cart.shopping_cart') as $sku => $cart) {
            
        foreach ($cart['shipping'] as $l => $details){

            if (isset($details) && $details == 'shipping') {
                $hasShipping = true;
                break; // Exit the loop if shipping is found
            }

        }

    }

    return $hasShipping;

}

function shipping_address(){

	$cart = session('checkoutCart');

	$address = [
	    "address"=>[
	        'city' => $cart['shipping']['address']['city'],
	        'country' => $cart['shipping']['address']['country_code'],
	        'line1' => $cart['shipping']['address']['line1'],
	        'line2' => $cart['shipping']['address']['line2'],
	        'postal_code' => $cart['shipping']['address']['zipcode'],
	        'state' => $cart['shipping']['address']['spr'],
	    ],
	    'name' => $cart['user']['contact_name'],
	    'email' => $cart['user']['email_address'],
	    'phone' => $cart['user']['phone'],
	];

    return $address;

}