<?php

use Illuminate\Support\Facades\Notification;

use App\Notifications\ProductCancelNotification;
use App\Notifications\ProductCancelRequestNotification;

use App\Notifications\ProductRefundNotification;
use App\Notifications\ProductTrackingAddedNotification;

use App\Notifications\ProductSoldNotification;
use App\Notifications\ProductPurchaseNotification;

use App\Notifications\ProductShippedNotification;

function ProductCancel($data){

	if ($data->user) {
        $data->user->notify(new ProductCancelNotification($data));
    }else{
        Notification::route('mail',$data->shipping_details['email'])->notify(new ProductCancelNotification($data));
    }

}

function ProductRequestCancel($data){
	$data->user->notify(new ProductCancelRequestNotification($data));
}

function ProductPurchase($data){
	if ($data->user) {
        $data->user->notify(new ProductPurchaseNotification($data));
    }else{
        Notification::route('mail', session('cart.address.email_address'))->notify(new ProductPurchaseNotification($data));
    }
}

function ProductRefund($data){
	if ($data->user) {
        $data->user->notify(new ProductRefundNotification($data));
    }else{
        Notification::route('mail',$data->shipping_details['email'])->notify(new ProductRefundNotification($data));
    }
}

function ProductShipped($data){
	if ($data->user) {
        $data->user->notify(new ProductShippedNotification($data));
    }else{
        Notification::route('mail',$data->shipping_details['email'])->notify(new ProductShippedNotification($data));
    }
}

function ProductSold($data){
	$data->user->notify(new ProductSoldNotification($data));
}

function TrackingAdded($data){
	Notification::route('mail',$data->shipping_details['email'])->notify(new ProductTrackingAddedNotification($data->order));
}