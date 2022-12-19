<div class="modal fade" id="add-card" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="exampleModalLabel"><b>Add Card</b></h5>
        <button type="button" class="icon-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
          <div>

            <form action="{{ url('user/payment-method') }}" method="POST" id="addPaymentFrm">

            @csrf
            <div>
            
                <x-stripe-card-info-input></x-stripe-card-info-input>
                <div>
                  <!-- <div class="mb-1"><b>Name On Card</b></div>
                  <input type="name" class="block w-full outline-none p-2 rounded-md border-gray-200 border text-xs mb-2" name="billing-name"> -->
                  <div class="mb-1"><b>Billing Address</b></div>
                  <div>
                     <x-billing-address></x-billing-address>
                  </div>
                </div>

            </div>

                 <div class="text-right pt-4">

                    <x-button flat label="Cancel" data-bs-dismiss="modal" />

                    <x-button type="submit" primary label="Add Card" />

                </div>

            </form>

          </div>
        
      </div>

    </div>
  </div>
</div>