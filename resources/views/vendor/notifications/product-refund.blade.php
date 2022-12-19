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



@if($order->refunded)
<div style="font-weight: bold;">Order Summary</div>
<div>Total Refunded - {{ $order->currency }} {{ $order->refunded['amount'] }}</div>
@endif

@if(isset($order->refunded['product']))
<div style="font-weight: bold;">Refund For Product</div>
<div style="padding: 12px 0px;">
    <div><img src="{{ Storage::disk('public')->url($order->refunded['product']->product_image.$order->refunded['product']->product_default_image) }}"></div>
    <div style="font-weight: bold;">{{ $order->refunded['product']->sku }}</div>
    <div>{{ $order->refunded['product']->name }}</div>
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
