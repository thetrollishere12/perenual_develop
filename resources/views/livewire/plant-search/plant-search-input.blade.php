<div class="max-w-7xl mx-auto p-2">
    
    <div class="py-2">
        
      <div class="text-sm inline relative">
          <div class="w-full">
            <input wire:model="search" placeholder="Search For A Plant" class="w-full border-none shadow mb-2 border-inherit text-sm p-2.5" type="text" autocomplete="off">
          </div>

      </div>
               
    </div>

    <div class="max-w-7xl mx-auto">

        <div id="search-container-display" class="grid gap-2 grid-cols-1 md:grid-cols-3 lg:grid-cols-4">

            @if(count($species) > 0)

            @foreach($species as $species)
            
                <a class="search-container-box shadow relative" href="{{ url('plant-database-seach-finder/species/'.$species->id) }}">
                    <div>
                        <div class="aspect-video bg-cover bg-center" style=" background-image:url('https://networkofnature.org/userContent/ecom/products/2626-7159/thumbs/7159_ABIEBAL_533.jpg');">
                        </div>

                        <div class="capitalize border-gray-200">

                            <div class="relative p-3">
                                <div>
                                    <div class="text-xl truncate">{{ $species->common_name }}</div>
                                    <div class="italic main-t-c">{{ $species->scientific_name }}</div>
                                </div>

                            </div>

                            <div class="border-t p-3">
                                <span class="text-xs ease-out duration-100 bg-black text-white hover:main-bg-c icon-heart-o rounded-full p-2 transition"></span>
                            </div>

                        </div>


                    </div>
                </a>

                <a class="search-container-box shadow relative" href="{{ url('plant-database-seach-finder/species/'.$species->id) }}">
                    <div>
                        <div class="aspect-video bg-cover bg-center" style=" background-image:url('https://networkofnature.org/userContent/ecom/products/2652-9215/finals/9215_ACERSPI_525.jpg');">
                        </div>

                        <div class="capitalize border-gray-200">

                            <div class="relative p-3">
                                <div class="text-xl truncate">{{ $species->common_name }}</div>
                                <div class="italic main-t-c">{{ $species->scientific_name }}</div>
                            </div>

                            <div class="border-t p-3">
                                <span class="text-xs ease-out duration-100 bg-black text-white hover:main-bg-c icon-heart-o rounded-full p-2 transition"></span>
                            </div>
                            
                        </div>
                    </div>
                </a>


                <a class="search-container-box shadow relative" href="{{ url('plant-database-seach-finder/species/'.$species->id) }}">
                    <div class="relative">
                        <div class="aspect-square bg-cover bg-center" style=" background-image:url('https://networkofnature.org/userContent/ecom/products/2652-9215/finals/9215_ACERSPI_525.jpg');">
                        </div>

                        <div class="w-full absolute bottom-0 opacity-90 capitalize border-gray-200" style="background: linear-gradient(transparent,black);">

                            <div class="relative p-3">
                                <div class="text-xl font-bold text-white truncate">{{ $species->common_name }}</div>
                                <div class="italic main-t-c">{{ $species->scientific_name }}</div>
                            </div>

                            <div class="border-t p-3">
                                <span class="text-xs ease-out duration-100 bg-black text-white hover:main-bg-c icon-heart-o rounded-full p-2 transition"></span>
                            </div>
                            
                        </div>
                    </div>
                </a>


            @endforeach

            @else

            <div>No Results Are Found!</div>

            @endif

        </div>

    </div>


</div>