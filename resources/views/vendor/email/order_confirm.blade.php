<div>
{{ $data->cus_order_number }}
</div>
<div>
{{ $data->user_id }}
</div>
<div>
{{ $data->payment_id }}
</div>
@if($data->shipping_details)
<div style="font-weight: bold;">Shipping To:</div>
<div>{{ $data->shipping_details['name'] }}</div>
<div>{{ $data->shipping_details['address']['line1'] }}</div>
<div>{{ $data->shipping_details['address']['city'] }}, {{ $data->shipping_details['address']['state'] }}</div>
<div>{{ $data->shipping_details['address']['postal_code'] }}</div>
<div>{{ country_code_to_string($data->shipping_details['address']['country']) }}</div>
@endif


