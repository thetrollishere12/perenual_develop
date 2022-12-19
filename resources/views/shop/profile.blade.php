@section('title')
{{ env('APP_NAME') }} | {{ $shop->name }}
@endsection
@section('description')
substr({{ $shop->description }},0,160)
@endsection

<x-app-layout>

    <div id="search-container" class="w-full min-h-screen">
        
        <div class="py-3">
            <div class="max-w-7xl px-2 mx-auto grid grid-cols-2">
            
                <div>

                    <img class="h-14 my-2 w-14 pr-1 object-cover" src="@if($shop->profile_photo_path) {{ Storage::disk('public')->url('shop-profile-photos/'.$shop->id.'/'.$shop->profile_photo_path) }} @else https://ui-avatars.com/api/?name={{ mb_substr($shop->name, 0, 1) }}&color=7F9CF5&background=EBF4FF @endif"/>

                    <div class="flex gap-1 items-center">
                       @for ($i = 0; $i < 5; $i++)
                          @if ($i < $shop->ratings)
                            <span class="text-amber-300">★</span>
                          @else
                            <span class="text-zinc-300">★</span>
                          @endif
                        @endfor
                        <span class="text-xs text-zinc-400">({{ $shop->ratings_count }})</span>
                    </div>

                    <div class="font-bold">{{ $shop->name }}</div>
                    <div><span class="icon-location2 text-xs px-0.5"></span><span class="text-sm">{{ $shop->country_name }}</span></div>

                    <div class="flex gap-1 pt-2">
                    @foreach($shop->socialMedia as $media)
                        <div class="relative">
                            <a href="{{ $media->url }}">
                                <div class="icon-{{$media->platform}}-box inline-block text-white rounded p-2">
                                    <span class="icon-{{$media->platform}}"></span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    </div>

                </div>

                <div class="justify-self-end">
                    
                    <a href="{{ url('users/profile/'.$user->id) }}">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button class="text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                                <img class="h-14 my-2 w-14 mx-auto rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                <div class="user-profile-name self-center ml-2">By <span class="username">{{ $user->name }}</span></div>
                            </button>         
                        @endif
                    </a>

                </div>

            </div>
        </div>

        
        <div class="max-w-7xl mx-auto p-2">
            @if(count($products) > 0)
            <div class="grid gap-2 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">
            @foreach($products as $product)

            <a class="text-center shadow relative" href="{{ url('marketplace/product/'.$product->sku) }}">
            <div>
              
                <div class="aspect-square relative bg-cover bg-center" style="background-image:url('{{ Storage::disk('public')->url($product->image.'thumbnail/'.$product->default_image) }}');"></div>

                <div class="text-center capitalize border-t border-gray-200">

                    <div class="relative py-2 px-1">

                        <div class="text-xs truncate">{{ $product->name }}</div>

                        <div class="text-sm pt-2 text-center main-t-c">

                            @if(isset($product->sale_price))
                            <div class="line-through text-green-500">{{ conversion($product->currency,$product->price,true) }}</div>
                            <div>{{ conversion($product->currency,$product->sale_price,true) }}</div>
                            @elseif(isset($product->variation))
                            <div>{{ conversion($product->currency,$product->price_range[0],true) }} - {{ conversion($product->currency,last($product->price_range),true) }}</div>
                            @else
                            <div class="font-bold">{{ conversion($product->currency,$product->price,true) }}</div>
                            @endif
                            
                        </div>

                        <!-- <div>
                            <div class="flex gap-1 items-center justify-center">
                            @for ($i = 0; $i < 5; $i++)
                              @if ($i < $product->ratings)
                                <span class="text-amber-300">★</span>
                              @else
                                <span class="text-zinc-300">★</span>
                              @endif
                            @endfor
                                <span class="text-xs text-zinc-400">({{ $product->ratings_count }})</span>
                            </div>

                        </div> -->

                        @if($product->shipping && $product->shipping == true)
                        <div><span class="rounded-full text-xs bg-green-100 inline-block mt-1 py-1 px-3">Free Shipping</span></div>
                        @endif
                        
                    </div>
                </div>
            </div>
            </a>

            @endforeach
            </div>
            <div class="pt-3">{{ $products->links('pagination::simple-tailwind') }}</div>
            @else

            <div>No Products</div>

            @endif
        </div>
        

    </div>

</x-app-layout>