<!-- 

<div class="modal fade" id="add-variation-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="exampleModalLabel"><b>Select Variation Image</b></h5>
        <button type="button" class="icon-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div>
            <div id="image-variation-list" class="grid grid-cols-8 gap-3">
                

            </div>
             <div class="text-right pt-4">
                <button type="button" id="save-variation-button" disabled class="bg-gray-200 px-8 py-1.5 text-sm text-white rounded">Save Image</button>
                <button type="button" class="bg-red-500 px-8 py-1.5 text-sm text-white rounded" data-bs-dismiss="modal">Close</button> 
            </div>
          </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
    
    $(document).on('click','button[name=add-variation-image]',function(){

        $('#image-variation-list').empty('');
        $('#save-variation-button').removeClass('main-bg-c').prop('disabled',true);

        $('.display-img').each(function(e){

            $('#image-variation-list').append('<div data-type="variation-img-click" data-count="variation-list-'+e+'" data-og="'+$(this).attr('data-og')+'" class="border border-slate-200 grid aspect-square content-center relative bg-contain bg-no-repeat bg-center"></div>');
            $('div[data-count=variation-list-'+e+']').css({'background-image':''+$(this).css('background-image')+''});

         });


    });


    $(document).on('click','div[data-type=variation-img-click]',function(){

        $('div[data-type=variation-img-click]').not($(this)).removeClass('border-warning');
        $(this).addClass('border-warning');
        $('#save-variation-button').addClass('main-bg-c').prop('disabled',false);

    });

    $(document).on('click','#save-variation-button',function(){



    });

</script> -->