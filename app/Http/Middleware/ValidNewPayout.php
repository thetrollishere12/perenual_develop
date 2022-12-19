<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidNewPayout
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

        if(get_payout() && valid_store()){
            return redirect('user/shop/product/create');
        }

        return $next($request);
    }
}
