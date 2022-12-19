<h3 id="similar-title" class="text-center py-3 text-xl">SIMILAR</h3>

<div class="splide splide_similar">
 <div class="splide__track">
       <ul class="splide__list text-center">
            @foreach($similars as $similar)               
             <li class="splide__slide text-center text-xs">
                <div class="p-8">
                   <a href="{{url('marketplace/product/'.$similar->sku)}}">

                      <div class="aspect-square w-full bg-cover bg-center" style="background-image:url('{{ Storage::disk('public')->url($similar->image.'thumbnail/'.$similar->default_image) }}');"></div>

                      <h3 class="text-xs py-2 truncate">{{$similar->name}}</h3>

                      <div class="text-sm text-center main-t-c">
                          @if(isset($similar->sale_price))
                          <div class="line-through text-green-500">{{ conversion($similar->currency,$similar->price,true) }}</div>
                          <div>{{ conversion($similar->currency,$similar->sale_price,true) }}</div>
                          @elseif(isset($similar->variation) && $similar->price == null)
                          <div>{{ conversion($similar->currency,$similar->price_range[0],true) }} - {{ conversion($similar->currency,last($similar->price_range),true) }}</div>
                          @else
                          <div class="font-bold">{{ conversion($similar->currency,$similar->price,true) }}</div>
                          @endif
                      </div>



                      <div class="pb-2">
                          <div class="flex gap-1 items-center justify-center">
                          @for ($i = 0; $i < 5; $i++)
                            @if ($i < $similar->store->ratings)
                              <span class="text-amber-300">★</span>
                            @else
                              <span class="text-zinc-300">★</span>
                            @endif
                          @endfor
                              <span class="text-xs text-zinc-400">({{ $similar->store->ratings_count }})</span>
                          </div>

                          <div class="text-xs text-zinc-400 truncate">{{ $similar->store->name }}</div>
                      </div>

                      <div class="flex justify-center gap-1">
                          @if($similar->shipping && $similar->shipping == true)
                          <div><span class="rounded-full text-xs bg-green-100 py-1 px-3">Free Shipping</span></div>
                          @endif
<!--                           @if($similar->store->local_pickup && env('LOCAL_PICKUP') == true)<div><span class="rounded-full text-xs bg-green-100 py-1 px-3">Pickup Available</span></div>@endif -->
                      </div>


                   </a>
                </div>
             </li>
            @endforeach
       </ul>
 </div>
</div>

<script type="text/javascript">

var splide = new Splide( '.splide_similar', {
  type   : 'loop',
  perPage: 5,
  perMove: 1,
  focus  : 'center',
  breakpoints: {
    1500: {
      perPage: 4,
     
    },
    1200: {
      perPage: 3,
     
    },
    1000: {
      perPage: 2,
  
    },
    700: {
      perPage: 1,

    },
  },
  updateOnMove : !0,
} );

splide.mount();   

</script>