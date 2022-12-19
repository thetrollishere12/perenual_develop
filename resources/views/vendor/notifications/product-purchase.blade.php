@component('mail::message')

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach


@if($order->shipping_details)
<div>
    <div style="font-weight: bold;">Shipping Address:</div>
    <div>{{ $order->shipping_details['name'] }}</div>
    <div>{{ $order->shipping_details['address']['line1'] }}</div>
    <div>{{ $order->shipping_details['address']['city'] }}, {{ $order->shipping_details['address']['state'] }}</div>
    <div>{{ $order->shipping_details['address']['postal_code'] }}</div>
    <div>{{ country_code_to_string($order->shipping_details['address']['country']) }}</div>
</div>
@endif


@if($order)
<div>
@foreach($order->order as $o)


<div style="font-weight: bold; padding: 0px 0px 20px 0px">#{{ $o->number }}</div>

@if($o->type == "pickup")
<div>
    <div style="font-weight: bold;">Pickup Address:</div>
    <div>{{ $o->store['name'] }}</div>
    <div>{{ $o->store_address['line1'] }}</div>
    <div>{{ $o->store_address['city'] }}, {{ $o->store_address['state'] }}</div>
    <div>{{ $o->store_address['postal_code'] }}</div>
    <div>{{ country_code_to_string($o->store_address['country']) }}</div>
</div>
@endif

<div style="font-weight: bold;">Order Summary</div>
<div>Subtotal - {{ $o->currency }} {{ $o->subtotal }}</div>
<div>Shipping - {{ $o->currency }} {{ $o->shipping }}</div>
<div>Tax - {{ $o->currency }} {{ $o->tax }}</div>
<div>Discount - {{ $o->currency }} {{ $o->discount }}</div>
<div>Total - {{ $o->currency }} {{ $o->total }}</div>

@if($o->order_products)
@foreach($o->order_products as $key => $product)
<div style="padding: 12px 0px;">
    <div><img src="{{ Storage::disk('public')->url($product->product_image.$product->product_default_image) }}"></div>
    <div style="font-weight: bold;">{{ $product->sku }}</div>
    <div>{{ $product->name }}</div>
    <div>Quantity - {{$product->quantity}}x ({{$product->currency_rate['foreign_currency']}} {{$product->to_price}})</div>
    <div>Total - {{ $product->currency_rate['foreign_currency'] }} {{ $product->quantity*$product->to_price }}</div>

    Estimated Delivery {{ Carbon\Carbon::now()->addDays($product->delivery_from)->format('M d') }} - {{ Carbon\Carbon::now()->addDays($product->delivery_to)->format('M-d') }}

</div>
@endforeach
@endif

@endforeach
</div>
@endif


{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent
