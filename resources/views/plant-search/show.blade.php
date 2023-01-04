<x-app-layout>
    @section('title')
    Plant Marketplace | Info & Guide On {{ $species->common_name }} ({{ $species->scientific_name }})
    @endsection
    @section('description')
    {{ $species->common_name }} ({{ $species->scientific_name }})
    @endsection
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/js/splide.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/css/splide.min.css">

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($species->common_name) }}
        </h1>
    </x-slot>
    
    <div class="bg-white">

    <div class="max-w-7xl mx-auto px-2.5 py-4">

        <div class="gap-8 grid md:grid-cols-5">
            
            <div class="col-span-2">
                <img class="w-full" src="https://networkofnature.org/userContent/ecom/products/2626-7159/thumbs/7159_ABIEBAL_533.jpg">
            </div>

            <div class="col-span-3">
                <div class="mb-2">
                    <h1 class="text-5xl font-bold">{{ $species->common_name }}</h1>
                    <h2 class="italic main-t-c">{{ $species->scientific_name }}</h2>
                </div>

                <div class="text-sm grid grid-cols-3 space-y-2 bg-gray-100 rounded p-3">
                    
                    @if($species->watering)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-droplet"></div>
                        <h4>Watering:</h4>
                        <p>{{ $species->watering }}</p>
                    </div>
                    @endif

                    @if($species->sun_exposure)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-brightness_low"></div>
                        <h4>Sun:</h4>
                        <p>{{ $species->sun_exposure }}</p>
                    </div>
                    @endif

                    @if($species->edible)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-spoon-knife"></div>
                        <h4>Edible:</h4>
                        <p>{{ $species->edible }}</p>
                    </div>
                    @endif

                    @if($species->poisonous)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-risk-skull"></div>
                        <h4>Poisonous:</h4>
                        <p>{{ $species->poisonous }}</p>
                    </div>
                    @endif

                    @if($species->flowering_season)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-flower"></div>
                        <h4>Flowering Season:</h4>
                        <p>{{ $species->flowering_season }}</p>
                    </div>
                    @endif
                    @if($species->fruiting_season)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-pear"></div>
                        <h4>Fruiting Season:</h4>
                        <p>{{ $species->fruiting_season }}</p>
                    </div>
                    @endif


                    @if($species->humidity)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-raindrops"></div>
                        <h4>Humidity:</h4>
                        <p>{{ $species->humidity }}</p>
                    </div>
                    @endif
                    @if($species->soil)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-shovel-_1"></div>
                        <h4>Soil:</h4>
                        <p>{{ $species->soil }}</p>
                    </div>
                    @endif
                    @if($species->hardiness)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-map"></div>
                        <h4>Hardiness Zone:</h4>
                        <p>{{ $species->hardiness }}</p>
                    </div>
                    @endif

                    @if($species->pruning)
                    <div class="flex items-center gap-1 capitalize">
                        <div class="icon-scissors"></div>
                        <h4>Pruning:</h4>
                        <p>{{ $species->pruning }}</p>
                    </div>
                    @endif







                </div>

            </div>

        </div>

        <div class="grid md:grid-cols-4 gap-4 mt-20">
            
            <div class="col-span-3 flex flex-col space-y-10">            

                <div>
                    
                    <div>
                        <div class="inline-block">
                            <h2 class="text-5xl">Habitat Considerations</h2>
                            <div class="border-b main-border-c my-2 mr-12 border-2"></div>
                        </div>
                    </div>

                    <div>Wild Sarsaparilla grows best in normal to moist conditions and prefers sand, loam, or rocky soils. It prefers neutral to acidic soils that are moderate to rich in nutrients, but will tolerate nutritionally poor soil. Wild Sarsaparilla is an understory species and prefers to grow in shade or partial shade. It is commonly found in woodlands, thickets, and along stream banks.</div>

                </div>

                
                <div>
                    <div class="inline-block">
                        <h2 class="text-5xl">Characteristics</h2>
                        <div class="border-b main-border-c my-3 mr-12 border-2"></div>
                    </div>
                </div>

            </div>

            <div class="border">
                12345
            </div>

        </div>

        <div>
            {{-- @livewire('plant-search.comment') --}}
            <livewire:plant-search.comment :product_id="$species->id"/>
        </div>

    </div>
    </div>
</x-app-layout>