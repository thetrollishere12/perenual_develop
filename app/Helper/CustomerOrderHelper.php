<?php

use App\Models\CustomerOrder;
use App\Models\Order;
use App\Models\User;

function get_customer_order($id){

	$cus_order = CustomerOrder::where('id',$id)->first();

	return $cus_order;

}

function get_customer_order_user($id){

	$cus_order = CustomerOrder::where('id',$id)->first();

	$cus_order->user = user_details($cus_order->user_id);

	return $cus_order;

}

function get_full_customer_order($id){

	$cus_order = CustomerOrder::where('id',$id)->first();

	$cus_order->user = user_details($cus_order->user_id);

	$cus_order->order = Order::where('cus_order_id',$cus_order->id)->get();

	foreach ($cus_order->order as $key => $order) {

		$order->fee = $order->marketplace_fee;

		// Get Store
		$order->store = get_store_by_id($order->store_id)->first();

		// Get Store User
		$order->user = user_details($order->store->user_id);

		// Get product list for order
		$order->order_products = get_order_products($order->number);

		if (env('LOCAL_PICKUP') == true && $order->type == 'pickup') {
			$order->store_address = get_store_address_by_id($order->store_id)->first();
		}

	}

	return $cus_order;

}