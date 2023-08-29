<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerOrder;
use Auth;
use App\Models\Store;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTracking;
use App\Models\RefundHistory;
use App\Models\User;
use Stripe;
use Redirect;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $orders = Order::leftjoin('customer_orders','orders.cus_order_id','=','customer_orders.id')
        ->select('orders.*','customer_orders.user_id as user_id','customer_orders.payment_method as payment_method','customer_orders.payment_id as payment_id','customer_orders.shipping_details as shipping_details')
        ->where('user_id',Auth::id())
        ->latest()
        ->paginate(5);

        foreach($orders as $order){

            $order->store = get_store_by_id($order->store_id)->first();

            $order->order_product = get_order_products($order->number);

            get_order_refund($order);

        }
        
        return view('profile.user.purchases.index',['orders'=>$orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($number)
    {

        try {

          $order = get_full_order($number)->where('user_id',Auth::id())->first();

          if (!$order) {
              return redirect('user/purchases');
          }

          return view('profile.user.purchases.show',["order"=>$order]);

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect('user/purchases');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $this->validate($request, [
            '_status' => 'required'
        ]);

        try {

            $order =  get_order($id)->where('user_id',Auth::id())->first();

            if ($order) {

                if ($request->_status == 'Request_Cancel') {
                    
                    Order::where('cus_order_id',$order->cus_order_id)
                    ->where('number',$id)
                    ->update([
                        'status'=>$request->_status,
                        'status_date'=>Carbon::now()
                    ]);

                    // Get Store
                    $order->store = get_store_by_id($order->store_id)->first();
                    // Get Store User
                    $order->user = user_details($order->store->user_id);

                    ProductRequestCancel($order);

                }

                return back();

            }

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect('user/purchases');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
