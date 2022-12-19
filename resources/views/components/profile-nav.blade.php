<div id="profile-nav-button" class="py-2 pl-2 border-b border-gray-200 block md:hidden cursor-pointer hover:bg-gray-100"><span class="icon-list"></span></div>

<div id="sidebar-option" class="bg-white md:min-h-screen w-full md:w-96 hidden md:block text-current z-1 relative border-b md:border-b-0 border-gray-200 text-lg">
    
    <div class="px-2 py-1">
        <div class="rounded transition p-3 text-sm">ACCOUNT</div>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/profile') }}"><div class="@if(isset($url) && $url == 'profile') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-user pr-3"></span> Profile</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/favorite') }}"><div class="@if(isset($url) && $url == 'favorite') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-heart pr-3"></span> Favorite</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/purchases') }}"><div class="@if(isset($url) && $url == 'purchase') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-cart pr-3"></span> Purchases</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/payment-method') }}"><div class="@if(isset($url) && $url == 'payment-method') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-credit-card pr-3"></span> Payment Method</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/address') }}"><div class="@if(isset($url) && $url == 'address') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-location2 pr-3"></span> Address</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/my-plants') }}"><div class="@if(isset($url) && $url == 'my-plants') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-flower pr-3"></span> My Plants</div></a>
    </div>

    @if(env('MARKETPLACE') == 'TRUE' && count($store) == 0)
    <div class="px-3 py-1">
        <a href="{{ url('shop/setup/get-started') }}"><div class="@if(isset($url) && $url == 'address') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-shop pr-3"></span> Start Selling</div></a>
    </div>
    @endif


    @if(env('MARKETPLACE') == 'TRUE' && count($store) > 0)

    <div class="px-2 py-1">
        <div class="rounded transition p-3 text-sm">SHOP</div>
    </div>

    
    <div class="px-3 py-1">
        <a href="{{ url('user/shop') }}"><div class="@if(isset($url) && $url == 'shop') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-newspaper pr-1 pr-3" style="font-size: 15px;"></span> Profile</div></a>
    </div>
    <div class="px-3 py-1">
        <a href="{{ url('user/shop/product') }}"><div class="@if(isset($url) && $url == 'products') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-price-tag pr-3" style="font-size: 15px;"></span> Products</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/shop/product/create') }}"><div class="@if(isset($url) && $url == 'product-create') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-pencil pr-3" style="font-size: 15px;"></span>Sell Product</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/shop/sold') }}"><div class="@if(isset($url) && $url == 'sold') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-attach_money pr-3" style="font-size: 15px;"></span> Sold Products</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/shop/shipping') }}"><div class="@if(isset($url) && $url == 'shipping') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-airplane pr-3" style="font-size: 15px;"></span> Shipping</div></a>
    </div>

    <div class="px-3 py-1">
        <a href="{{ url('user/shop/payout') }}"><div class="@if(isset($url) && $url == 'payout') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-library pr-3" style="font-size: 15px;"></span> Payout Settings</div></a>
    </div>
    <div class="px-3 pt-1 pb-3">
        <a href="{{ url('user/shop/transaction-history') }}"><div class="@if(isset($url) && $url == 'transaction_history') main-bg-c text-white @else hover:bg-gray-100 @endif rounded transition p-3 text-sm"><span class="icon-stats-bars pr-3" style="font-size: 15px;"></span> Transaction History</div></a>
    </div>
   

    @endif

</div>

<script type="text/javascript">
    
    $('#profile-nav-button').click(function(){
        $('#sidebar-option').slideToggle();
    });

</script>