    $(".currency-li").click(function(){
        $.ajax({
            url: window.origin +"/currency",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            method: "POST",
            data: {
                currency:$(this).attr('value')
            },
            success: function (c) {
                window.$wireui.notify({
                    title: 'Changing Currency',
                    description: 'Please wait while you are being redirected to the page with the selected currency',
                    icon: 'success'
                });
                location.reload();
            },
            error: function (c, r, t) {
                window.$wireui.notify({
                    title: 'Ran Into A Problem',
                    description: 'There was a problem trying to change the currency. Please try again or contact us',
                    icon: 'error'
                });
                location.reload();
            },
        });
    });

    $(".country-li").click(function(){

        $.ajax({
            url: window.origin +"/country",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            method: "POST",
            data: {
                country:$(this).attr('value')
            },
            success: function (c) {
                window.$wireui.notify({
                    title: 'Changing Location',
                    description: 'Please wait while you are being redirected to the page with the selected location',
                    icon: 'success'
                });
                location.reload();
            },
            error: function (c, r, t) {
                window.$wireui.notify({
                    title: 'Ran Into A Problem',
                    description: 'There was a problem trying to change the location. Please try again or contact us',
                    icon: 'error'
                });
                location.reload();
            },
        });
    });

    $(document).on('submit','form',function(e){
        $(this).find('button[type=submit]').not('.undisabled').prop('disabled', true);
    });

    $('.nav-link, .nav-dropdown').hover(function(){
        $(".nav-dropdown[attr='"+$(this).attr('attr')+"']").show(); 
    },function(){
        $(".nav-dropdown").hide();
    });

    $(".responsive-nav-link").click(function () {
        $(this).next().slideToggle();
    })

    $(document).on('change','select[name=country]',function(){
       var val = $(this).children("option:selected").attr("id");
       $.getJSON(country, function (e) {
           $('input[name=state_county_province_region],select[name=state_county_province_region]').remove();
           if (e[val].states.length > 0) {
               $('input[name=city]').after('<select class="block w-full outline-none p-2 border-x border-t border-b-0 text-xs border-gray-200" name="state_county_province_region" required></select>');
               for (var i = 0; i < e[val].states.length; i++) {
                   $('select[name=state_county_province_region]').append('<option value="' + e[val].states[i] + '">' + e[val].states[i] + "</option>");
               }
           }else{
               $('input[name=city]').after('<input class="block w-full outline-none p-2 border-x border-t border-b-0 text-xs border-gray-200" required placeholder="Province County State Region" name="state_county_province_region">');
           }
       });
   });

    $(document).on('input', function(e) {
    // Max number for input
      if ($(e.target).val() > parseInt($(e.target).attr('max'))) {
        $(e.target).val($(e.target).attr('max'));
      }
    // Max length for input
      if ($(e.target).val().length > parseInt($(e.target).attr('maxlength'))) {
        $(e.target).val($(e.target).val().slice(0,$(e.target).attr('maxlength')));
      }
      
    });

    document.addEventListener('livewire:load', () => {
        Livewire.onPageExpired((response, message) => {
            $('#expiredModal').modal('show');
        });
    });