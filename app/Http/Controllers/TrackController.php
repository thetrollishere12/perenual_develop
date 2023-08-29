<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;

class TrackController extends Controller
{
    
    public function index(){
        return view('shopping.tracking');
    }

    public function post(Request $req){

        $validate = $this->validate($req, [
            'email' => 'required|email',
            'order_number' => 'required|string|starts_with:O_,o_'
        ]);

        $user = User::where('email',$req->email)->select('id','name','email')->first();

        $order = Order::where('number',$req->order_number)
        ->leftjoin('customer_orders','orders.cus_order_id','=','customer_orders.id')
        ->where('customer_orders.user_id',$user->id)
        ->select('orders.*','customer_orders.user_id as user_id','customer_orders.payment_method as payment_method','customer_orders.payment_id as payment_id','customer_orders.created_at as created_at')
        ->first();
        
        if (!$user || !$order) {
            return redirect()->back()->withErrors(['Order Does Not Exist']);
        }

        $order->order_product = get_order_products($order->number);

        return view('shopping.tracking-result',['order'=>$order]);

    }

}
