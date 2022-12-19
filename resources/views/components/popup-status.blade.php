<!-- <div id="popup-container" class="rounded px-14 py-3 text-white shadow-lg hidden z-20 translate-x-2/4 translate-y-2/4 fixed top-3/4 left-2/4 transform -translate-x-2/4 -translate-y-2/4">
    <div id="popup-message">Status has been saved</div>
</div>
 -->
<script type="text/javascript">
    
    function popup(color,message){

        $("body").append('<div id="popup-container" class="w-11/12 md:w-auto text-center rounded px-14 py-3 text-white shadow-lg hidden z-20 fixed bottom-0 md:bottom-auto md:top-2/3 left-2/4 transform -translate-x-2/4 -translate-y-2/4"><div id="popup-message">Status has been saved</div></div>');

        switch(color){

            case "red":
            $("#popup-container").css({"background":"#ef4444"});
            break;

            case "green":
            $("#popup-container").css({"background":"#34d399"});
            break;

            default:
            $("#popup-container").css({"background":color});
        }

        $("#popup-message").text(message);
        $("#popup-container").stop(true).fadeIn().delay(2000).fadeOut();

    }

</script>