<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class ValidShoppingCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // If allows guest checkout
        if(env('ANONYMOUS_SHOPPING') != 'TRUE' && !Auth::user()){
            return redirect('shopping-cart')->withErrors('Please sign in to continue');
        }

        // Check if cart exist and has products
        if(!session('cart.shopping_cart') && empty(session('cart.shopping_cart'))){
            return redirect()->to('shopping-cart');
        }

        return $next($request);
    }
}
