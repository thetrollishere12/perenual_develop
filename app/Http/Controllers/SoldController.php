<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

use App\Models\OrderTracking;
use App\Models\User;
use App\Models\RefundHistory;
use App\Models\PayoutHistory;
use App\Models\Payout;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use Stripe;

use App\Models\ShopConfirmOrder;

class SoldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $store = get_store()->first();

        $orders = ShopConfirmOrder::leftjoin('customer_orders','shop_confirm_orders.cus_order_id','=','customer_orders.id')
        ->select('shop_confirm_orders.*','customer_orders.user_id as user_id','customer_orders.payment_method as payment_method','customer_orders.payment_id as payment_id','customer_orders.shipping_details as shipping_details')
        ->where('store_id',$store->id)
        ->latest()
        ->paginate(5);

        foreach($orders as $order){

            $order->order_product = get_order_products($order->number);

            get_order_refund($order);

            $order->order_details = get_order($order->number)->first();

            $order->tracking = get_order_tracking($order);

        }

        return view('profile.shop.order.index',["orders"=>$orders]);


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

            $store = get_store()->first();

            $order = get_sold_full_order($number)->where('store_id',$store->id)->first();

            if (!$order) {
              return redirect('user/purchases');
            }

            $order->order_details = get_order($number)->first();

            return view('profile.shop.order.show',["order"=>$order]);

        } catch (\Exception $e) {

            return redirect('user/shop/sold');
        }

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

        $store = get_store()->first();

        try {

        $order = Order::where('number', $id)->where('store_id', $store->id)->first();

        if ($order && $order->status != $request->_status) {
            // Update if the status is different
            $order->update([
                'status' => $request->_status,
                'status_date' => Carbon::now()
            ]);
        }
        
        if ($request->_status == 'Shipped') {

            $order = get_order($id)->where('store_id',$store->id)->first();
            
            ProductShipped($order);
        }

        return back();

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tracking(Request $request)
    {

        $this->validate($request, [
            'order_number' => 'required',
            'courier' => 'required',
            'trackingNumber' => 'required'
        ]);

        $store = get_store()->first();

        $order = Order::where('number',$request->order_number)->where('store_id',$store->id)->get();

        if ($order->count() > 0) {

            $tracking = OrderTracking::updateOrCreate([
                'number' => $order->first()->number
            ],
            [
                'number' => $order->first()->number,
                'courier' => $request->courier,
                'tracking' => $request->trackingNumber,
            ]);

            $cus_order = get_customer_order($order->first()->cus_order_id);
            $cus_order->order = $order->first();
            TrackingAdded($cus_order);

            return back();

        }

    }

    public function get_refund($number){

        try {

            $store = get_store()->first();

            $order = ShopConfirmOrder::where('number',$number)->where('store_id',$store->id)->first();

            if (!$order) {
              return redirect('user/shop/sold');
            }

            return view('profile.shop.order.refund',["number"=>$number]);

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect('user/shop/sold');
        }

    }

}
