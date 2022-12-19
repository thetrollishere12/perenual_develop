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

<div style="font-weight: bold; padding: 0px 0px 20px 0px">#{{ $order->number }}</div>

@if($order->shipping_details)
<div style="font-weight: bold;">Shipping Address:</div>
<div>{{ $order->shipping_details['name'] }}</div>
<div>{{ $order->shipping_details['address']['line1'] }}</div>
<div>{{ $order->shipping_details['address']['city'] }}, {{ $order->shipping_details['address']['state'] }}</div>
<div>{{ $order->shipping_details['address']['postal_code'] }}</div>
<div>{{ country_code_to_string($order->shipping_details['address']['country']) }}</div>
@endif

@if($order->tracking)
<div style="font-weight: bold;">Tracking</div>
<div>{{ $order->tracking->courier }}</div>
<div>{{ $order->tracking->tracking }}</div>
@endif

@if($order)
<div style="font-weight: bold;">Order Summary</div>
<div>Subtotal - {{ $order->currency }} {{ $order->subtotal }}</div>
<div>Shipping - {{ $order->currency }} {{ $order->shipping }}</div>
<div>Tax - {{ $order->currency }} {{ $order->tax }}</div>
<div>Discount - {{ $order->currency }} {{ $order->discount }}</div>
<div>Total - {{ $order->currency }} {{ $order->total }}</div>
@endif

@if($order->products)
@foreach($order->products as $key => $product)
<div style="padding: 12px 0px;">
    <div><img src="{{ Storage::disk('public')->url($product->product_image.$product->product_default_image) }}"></div>
    <div style="font-weight: bold;">{{ $product->sku }}</div>
    <div>{{ $product->name }}</div>
    <div>Quantity - {{$product->quantity}}x ({{$product->currency_rate['base_currency']}} {{$product->from_price}})</div>
    <div>Total - {{ $product->currency_rate['base_currency'] }} {{ $product->quantity*$product->from_price }}</div>
</div>
@endforeach
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
