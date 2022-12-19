<div class="modal fade" id="add-tracking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="exampleModalLabel"><b>Add Tracking</b></h5>
        <button type="button" class="icon-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
          <div>

            <form action="{{ url('user/shop/sold/tracking/add') }}" method="POST" id="addPaymentFrm">

                @csrf
                @method('PUT')
                <div>
                    
                    <select name="courier" class="text-sm border w-full border-inherit rounded mb-2">
                        <option value="FedEx">FedEx</option>
                        <option value="UPS">UPS</option>
                        <option value="USPS">USPS</option>
                        <option value="Royal Mail">Royal Mail</option>
                        <option value="ZTO Express">ZTO Express</option>
                        <option value="DHL">DHL</option>
                        <option value="YRC">YRC</option>
                        <option value="Australia Post">Australia Post</option>
                        <option value="Canada Post">Canada Post</option>
                        <option value="Other">Other</option>
                    </select>

                    <input type="text" name="trackingNumber" placeholder="Tracking Number/Code" class="text-sm border w-full border-inherit rounded">

                </div>

                 <div class="text-right pt-4">
                    <x-jet-button type="submit" class="main-bg-c">
                    {{ __('Add Tracking') }}
                    </x-jet-button>
                    <x-jet-button type="button" class="bg-red-500" data-bs-dismiss="modal">
                    {{ __('Close') }}
                    </x-jet-button>
                </div>

                <input type="hidden" name="order_number" value="">

            </form>

          </div>
        
      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
    
$('#add-tracking').on('show.bs.modal', function (e) {
    $('input[name=order_number]').val($(e.relatedTarget).attr('data-num'));
});

</script>