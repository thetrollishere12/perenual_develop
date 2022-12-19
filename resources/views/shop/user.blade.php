<x-guest-layout>

    <div id="search-container" class="w-full min-h-screen">
        
        <div>
            <div class="max-w-7xl py-3 px-2 mx-auto grid grid-cols-2">
            
                <div>
                    
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="text-sm border-2 border-transparent rounded-full focus:outline-none transition flex">
                            <img class="h-14 my-2 w-14 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />

                            <div class="self-center">
                                <div class="ml-2"><span class="username">{{ $user->name }}</span></div>
                                @if($user->shop)
                                <a href="{{ url('shop/profile').'/'.$user->shop->id.'/'.$user->shop->name }}">
                                    <div class="flex ml-2">
                                        <img class="h-8 w-8 my-1 object-cover" src="@if($user->shop->profile_photo_path) {{ Storage::disk('public')->url('shop-profile-photos/'.$user->shop->id.'/'.$user->shop->profile_photo_path) }} @else https://ui-avatars.com/api/?name={{ mb_substr($user->shop->name, 0, 1) }}&color=7F9CF5&background=EBF4FF @endif "/>
                                        <div class="text-xs self-center pl-2">{{ $user->shop->name }}</div>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>         
                    @endif

                </div>
            </div>
        </div>

        <div class="max-w-7xl px-2 mx-auto">

            <h2 class="my-4 font-bold text-4xl">Their Plants</h2>

            <div id="search-container-display" class="grid gap-2 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">

                @if(count($user->plants) > 0)

                @foreach($user->plants as $plant)

                <a href="{{ url('users/profile/'.$user->id.'/plant/'.$plant->plant_id) }}">
                <div class="search-container-box text-center shadow relative">
                  
                    <div class="aspect-square relative bg-cover bg-center" style="background-image:url('{{ Storage::disk('public')->url($plant->image.$plant->default_image) }}');"></div>

                    <div class="text-center capitalize border-t border-gray-200">

                        <div class="relative">

                            <div class="text-xs p-2 overflow-clip overflow-hidden">{{ $plant->name }}</div>

                        </div>

                    </div>

                </div>
                </a>

                @endforeach

                @else

                <div>No Favorites</div>

                @endif

            </div>

            <h2 class="my-4 font-bold text-4xl">Favorites</h2>

            <div class="grid gap-2 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">

                @if(count($user->favorites) > 0)

                @foreach($user->favorites as $favorite)

                <a href="{{ url('marketplace/product/'.$favorite->sku) }}">
                <div class="search-container-box text-center shadow relative">
                  
                    <div class="aspect-square relative bg-cover bg-center" style="background-image:url('{{ Storage::disk('public')->url($favorite->image.$favorite->default_image) }}');"></div>

                    <div class="text-center capitalize border-t border-gray-200">

                        <div class="relative">

                            @for ($i = 0; $i < 5; $i++)
                              @if ($i < $favorite->ratings)
                                <span class="text-amber-300">★</span>
                              @else
                                <span class="text-zinc-300">★</span>
                              @endif
                            @endfor

                            <div class="text-xs p-2 overflow-clip overflow-hidden">{{ $favorite->name }}</div>

                            <div class="text-sm text-center main-t-c pb-2">

                                @if(isset($favorite->sale_price))
                                <div class="line-through text-green-500">{{ conversion($favorite->currency,$favorite->price,true) }}</div>
                                <div>{{ conversion($favorite->currency,$favorite->sale_price,true) }}</div>
                                @elseif(isset($favorite->variation))
                                <div>{{ conversion($favorite->currency,$favorite->price_range[0],true) }} - {{ conversion($favorite->currency,last($favorite->price_range),true) }}</div>
                                @else
                                <div>{{ conversion($favorite->currency,$favorite->price,true) }}</div>
                                @endif
                                
                            </div>


                        </div>

                    </div>

                </div>
                </a>

                @endforeach

                @else

                <div>No Favorites</div>

                @endif

            </div>

        </div>

    </div>

</x-guest-layout>