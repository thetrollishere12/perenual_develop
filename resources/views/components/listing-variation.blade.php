<div class="modal fade" id="add-variation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="exampleModalLabel"><b>Add Variation</b></h5>
        <button type="button" class="icon-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div>
            <div class="text-xs">
                <div id="variation-list">
                    <div class="variation py-2 grid grid-cols-2">
                        <div class="pr-2">
                            <div class="pb-2">
                                <input class="block w-full outline-none p-2 rounded-md border border-gray-200 text-xs" @if(isset($name))value="{{ $name }}" @endif placeholder="Variation Name" type="text" name="variation_name">
                            </div>
                            <div class="p-1">
                                <input type="checkbox" @if($price == '1') checked @endif class="border" name="price_vary"><span class="pl-1">Prices vary</span>
                            </div>
                            <div class="p-1">
                                <input type="checkbox" @if($quantity == '1') checked @endif class="border" name="quantity_vary"><span class="pl-1">Quantity vary</span>
                            </div>
                        </div>
                        <div class="pl-2">
                            <div class="input-group">
                                <input type="text" name="option_name" class="form-control w-full outline-none p-2 rounded-l-md border-gray-200 border text-xs" placeholder="Option Name" />
                                <button class="add_option_btn bg-neutral-900 px-4 text-xs text-white rounded-r-md">Add</button>
                            </div>
                            <div class="option_list"></div>
                        </div>
                    </div>
                </div>
                <!-- <div id="add-variation-ctn">
                    <button name="add-variation" class="bg-neutral-900 px-3 py-1.5 text-xs text-white rounded">Add Another Variation</button>
                </div> -->
            </div>

             <div class="text-right pt-4">

                    <x-jet-button onclick="save_shipping_profile();" type="button" class="main-bg-c">
                    {{ __('Save Variation') }}
                    </x-jet-button>

                    <x-jet-button type="button" class="bg-red-500" data-bs-dismiss="modal">
                    {{ __('Close') }}
                    </x-jet-button>

                <button onclick="save_shipping_profile();" type="button" class="main-bg-c px-8 py-1.5 text-sm text-white rounded">Save Variation</button>
                <button type="button" class="bg-red-500 px-8 py-1.5 text-sm text-white rounded" data-bs-dismiss="modal">Close</button> 
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    
    // Options

    $(document).on('click','.add_option_btn',function(){

        if ($(this).prev().val() == '') {
            $(this).prev().addClass('border-red-500').removeClass('border-gray-200 border');
            popup('red','Name Cannot Be Empty');
        }else{

        $(this).parent().next().append('<div class="rounded border my-1 p-2 flex justify-between"><div name="options">'+$(this).prev().val()+'</div><div><span class="delete-option icon-close cursor-pointer"></span></div></div>');
        $(this).prev().addClass('border border-gray-200').removeClass('border-red-500');
        $(this).prev().val('');

        }

    });

    $(document).on('click','.delete-option',function(){
        $(this).parent().parent().remove();
    });

    // Additional Variation

    // $(document).on('click','button[name=add-variation]',function(){
    //     $("#variation-list").append('<div class="variation_2 py-2 grid grid-cols-2"> <div class="pr-2"> <div class="pb-2"> <input class="block w-full outline-none p-2 rounded-md border-gray-200 border text-xs" placeholder="Variation Name" type="text" name="variation_name"> </div><div class="p-1"> <input type="checkbox" class="border" name="price_vary"><span class="pl-1">Prices vary</span> </div><div class="p-1"> <input type="checkbox" class="border" name="quantity_vary"><span class="pl-1">Quantity vary</span> </div><div><button class="delete-variation bg-red-500 px-3 py-1 mt-1 text-white rounded">Delete</button></div></div><div class="pl-2"> <div class="input-group"> <input type="text" name="option_name" class="form-control w-full outline-none p-2 rounded-l-md border-gray-200 border text-xs" placeholder="Option Name"/> <button class="add_option_btn bg-neutral-900 px-4 text-xs text-white rounded-r-md">Add</button> </div><div class="option_list"></div></div></div>');

    //     $(this).remove();
    // });

    // $(document).on('click','.delete-variation',function(){
    //     $('.variation_2').remove();
    //     $('#add-variation-ctn').append('<button name="add-variation" class="bg-neutral-900 px-3 py-1.5 text-xs text-white rounded">Add Another Variation</button>');
    // });



    function save_shipping_profile(){

        $('.variation-option-container').html('');

        $('input[name=variation_name]').each(function(e){

            if ($(this).val().length === 0) {
                $(this).addClass('border-red-500').removeClass('border border-gray-200');
                e.preventDefault();
            }else{
                $(this).addClass('border border-gray-200').removeClass('border-red-500');
            }

        });

        $('.option_list').each(function(e){

            if ($(this).find('.border').length === 0) {
                $(this).prev().find('input[name=option_name]').addClass('border-red-500').removeClass('border-gray-200 border');
                e.preventDefault();
            }else{
                $(this).prev().find('input[name=option_name]').addClass('border border-gray-200').removeClass('border-red-500');
            }

        });

        var price = $('.variation').find($('input[name=price_vary]')).is(':checked')
        var quantity = $('.variation').find($('input[name=quantity_vary]')).is(':checked')

        var price_2 = $('.variation_2').find($('input[name=price_vary]')).is(':checked')
        var quantity_2 = $('.variation_2').find($('input[name=quantity_vary]')).is(':checked')

        if ($('button[data-type=delete_variation]').length == 0) {

            $('#variation-btn-list').append('<button type="button" class="bg-red-500 text-xs mt-2 cursor-pointer text-center rounded text-white px-3 py-2" data-type=delete_variation onclick="delete_variation()">Delete Variation</button>');

        }

        if (price == true || price_2 == true) {
            $('.price_case').empty();
        }else{

            if ($('.price_case').find('input[name=price]').length == 0) {
                $('.price_case').empty().append('<input min="1" type="number" name="price" required class="block w-full outline-none p-2 rounded-md border-gray-200 border text-xs mb-2" placeholder="Product Pricing">');
            }

        }

        if (quantity == true || quantity_2 == true) {
            $('.quantity_case').empty();
        }else{
            if ($('.quantity_case').find('input[name=unlimited]').length == 0) {
                $('.quantity_case').empty().append('<input min="1" type="number" required placeholder="Quantity" name="quantity" class="outline-none p-2 rounded-md border-gray-200 border text-xs mb-2">');
            }
        }

        $('.variation').each(function(){

            $('.variation-option-container').append('<tr> <th class="border p-2 text-xs"><input name="variation_name[]" value="'+$(this).find($('input[name=variation_name]')).val()+'" readonly class="pointer-events-none border-0 bg-white"></th> <th class="border p-2 text-xs"></th> <th class="border p-2 text-xs"></th><th class="border p-2 text-xs">Quantity</th><th class="border p-2 text-xs">Price</th> </tr>')

                $(this).find('div[name=options]').each(function(e,a){

                    $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"></td> <td class="border p-2 text-xs"></td> <td class="border p-2 text-xs">'+((quantity == true) ? '<input min="1" placeholder="Quantity" required class="outline-none p-1.5 border text-xs" name="option_quantity[]">' : '<div></div>')+'</td><td class="border p-2 text-xs">'+((price == true) ? '<input min="1" placeholder="Price" required class="outline-none p-1.5 border text-xs" name="option_price[]">' : '<div></div>')+'</td> </tr>')

                    // $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"></td> <td class="border p-2 text-xs"><button name="add-variation-image" data-bs-toggle="modal" data-bs-target="#add-variation-image" type="button" class="bg-neutral-900 text-xs cursor-pointer text-center rounded text-white px-2 py-1">Link Image</button></td> <td class="border p-2 text-xs">'+((quantity == true) ? '<input placeholder="Quantity" required class="outline-none p-1.5 border text-xs" name="option_quantity[]">' : '<div></div>')+'</td><td class="border p-2 text-xs">'+((price == true) ? '<input placeholder="Price" required class="outline-none p-1.5 border text-xs" name="option_price[]">' : '<div></div>')+'</td> </tr>')

                });
        });

        // Validate Name & Option

        // if ($('.variation_2').length) {

        //     switch(true){
        //         case price == true && price_2 == true:
        //             var is_price = true;
        //             break;
        //         case quantity == true && quantity_2 == true:
        //             var is_quantity = true;
        //             break;
        //         default:
        //             var is_quantity = false;
        //             var is_price = false;
        //             break;
        //     }

        //     if (is_quantity == false || is_price == false) {

        //         // If No Same

        //         $('.variation').each(function(){

        //             $('.variation-option-container').append('<tr> <th class="border p-2 text-xs"><input name="variation_name[]" value="'+$(this).find($('input[name=variation_name]')).val()+'" readonly class="pointer-events-none border-0 bg-white"></th> <th class="border p-2 text-xs"></th> <th class="border p-2 text-xs">Quantity</th><th class="border p-2 text-xs">Price</th> </tr>')

        //                 $(this).find('div[name=options]').each(function(e,a){

        //                     $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"></td> <td class="border p-2 text-xs">'+((quantity == true) ? '<input placeholder="Quantity" class="outline-none p-1.5 border text-xs" name="option_quantity[]">' : '<input placeholder="Quantity" type="hidden" class="outline-none p-1.5 border text-xs" name="option_quantity[]">')+'</td><td class="border p-2 text-xs">'+((price == true) ? '<input placeholder="Price" class="outline-none p-1.5 border text-xs" name="option_price[]">' : '<input placeholder="Quantity" type="hidden" class="outline-none p-1.5 border text-xs" name="option_price[]">')+'</td> </tr>')
        //                 });
        //         });


        //         $('.variation_2').each(function(){

        //             $('.variation-option-container').append('<tr> <th class="border p-2 text-xs"><input name="variation_name[]" value="'+$(this).find($('input[name=variation_name]')).val()+'" readonly class="pointer-events-none border-0 bg-white"></th> <th class="border p-2 text-xs"></th> <th class="border p-2 text-xs">Quantity</th><th class="border p-2 text-xs">Price</th> </tr>')

        //                 $(this).find('div[name=options]').each(function(e,a){

        //                     $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"></td> <td class="border p-2 text-xs">'+((quantity_2 == true) ? '<input placeholder="Quantity" class="outline-none p-1.5 border text-xs" name="option_quantity[]">' : '<input placeholder="Quantity" type="hidden" class="outline-none p-1.5 border text-xs" name="option_quantity[]">')+'</td><td class="border p-2 text-xs">'+((price_2 == true) ? '<input placeholder="Price" class="outline-none p-1.5 border text-xs" name="option_price[]">' : '<input placeholder="Quantity" type="hidden" class="outline-none p-1.5 border text-xs" name="option_price[]">')+'</td> </tr>')
        //                 });
        //         });

        //     }else{

        //         // Only 1 same

        //         $('.variation-option-container').append('<tr> <th class="border p-2 text-xs"><input name="variation_name[]" value="'+$('.variation').find($('input[name=variation_name]')).val()+'" readonly class="pointer-events-none border-0 bg-white"></th> <th class="border p-2 text-xs"><input name="variation_name[]" value="'+$('.variation_2').find($('input[name=variation_name]')).val()+'" readonly class="pointer-events-none border-0 bg-white"></th> <th class="border p-2 text-xs">Quantity</th><th class="border p-2 text-xs">Price</th> </tr>')

        //         if ((is_quantity == true && price == false && price_2 == false) || (is_price == true && quantity == false && quantity_2 == false)) {

        //             $('.variation').find('div[name=options]').each(function(e,a){

        //                 $('.variation_2').find($('div[name=options]')).each(function(r,t){

        //                      $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(t).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs">'+((quantity == true && quantity_2 == true) ? '<input placeholder="Quantity" class="outline-none p-1.5 rounded border text-xs" name="option_quantity[]">' : '<div></div>')+'</td><td class="border p-2 text-xs">'+((price == true && price_2 == true) ? '<input placeholder="Price" class="outline-none p-1.5 rounded border text-xs" name="option_price[]">' : '<div></div>')+'</td> </tr>')
        //                 });
        //             });

        //         }else{

        //             // Duel

        //             $('.variation').find('div[name=options]').each(function(e,a){

        //                 $('.variation_2').find($('div[name=options]')).each(function(r,t){

        //                      $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name_1[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"><input name="option_name_2[]" value="'+$(t).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"><input placeholder="Quantity" class="outline-none p-1.5 rounded border text-xs" name="option_quantity[]"></td><td class="border p-2 text-xs"><input placeholder="Price" class="outline-none p-1.5 rounded border text-xs" name="option_price[]"></td> </tr>')
        //                 });
        //             });
        //         }
        //     }

        // }else{

        //     // If second variation doesnt exist

        //     $('.variation').each(function(){

        //         $('.variation-option-container').append('<tr> <th class="border p-2 text-xs"><input name="variation_name[]" value="'+$(this).find($('input[name=variation_name]')).val()+'" readonly class="pointer-events-none border-0 bg-white"></th> <th class="border p-2 text-xs"></th> <th class="border p-2 text-xs">Quantity</th><th class="border p-2 text-xs">Price</th> </tr>')

        //             $(this).find('div[name=options]').each(function(e,a){

        //                 $('.variation-option-container').append('<tr> <td class="border p-2 text-xs"><input name="option_name[]" value="'+$(a).text()+'" readonly class="pointer-events-none border-0 bg-white"></td> <td class="border p-2 text-xs"></td> <td class="border p-2 text-xs">'+((quantity == true) ? '<input placeholder="Quantity" required class="outline-none p-1.5 border text-xs" name="option_quantity[]">' : '<div></div>')+'</td><td class="border p-2 text-xs">'+((price == true) ? '<input placeholder="Price" required class="outline-none p-1.5 border text-xs" name="option_price[]">' : '<div></div>')+'</td> </tr>')
        //             });
        //     });
        // }

        $('#add-variation').modal('hide');
        popup('green','Variation Added');

    }


    function delete_variation(){
 
        $('.quantity_case').empty().append('<input min="1" type="number" required placeholder="Quantity" name="quantity" class="outline-none p-2 rounded-md border-gray-200 border text-xs mb-2">');

        $('.price_case').empty().append('<input min="1" type="number" name="price" required="" class="w-80 block w-full outline-none p-2 rounded-md border-gray-200 border text-xs mb-2" placeholder="Product Pricing">');

        $('.variation-option-container').empty();
        $('button[data-type=delete_variation]').remove();

        }


</script>