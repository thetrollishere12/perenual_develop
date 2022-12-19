<div class="mb-1"><b>Card Information</b></div>
<div id="paymentResponse" class="text-red-500 text-xs py-1"></div>
<div class="credit-card-container rounded-md border border-gray-200 my-2">
  <div class="form-group card-number-container p-2.5 border-b border-gray-200 relative">
     <div id="card_number" class="field"></div>
     <div class="flex gap-1 absolute top-2.5 right-2">
          <img src="{{ Storage::disk('public')->url('image/visa.svg') }}">
          <img src="{{ Storage::disk('public')->url('image/mastercard.svg') }}">
          <img src="{{ Storage::disk('public')->url('image/amex.svg') }}">
          <img src="{{ Storage::disk('public')->url('image/discover.svg') }}">
     </div>
  </div>
  <div class="card-container-ex-cvc grid grid-cols-2">
     <div class="form-group expiry-date-container border-r border-gray-200 p-2.5">
        <div id="card_expiry" class="field"></div>
     </div>
     <div class="form-group cvc-container p-2.5">
        <div id="card_cvc" class="field"></div>
     </div>
  </div>
</div>