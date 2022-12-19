<div class="grid gap-2 grid-cols-1 md:grid-cols-3 lg:grid-cols-4 max-w-7xl">

    <a href="{{ url('user/my-plants/create') }}">
        <div class="bg-white text-center shadow min-h-full h-80 flex items-center justify-center">
            
            <div>
                
                <div class="icon-product-ex-1 text-5xl mb-3"></div>
                <div>Add My Plant</div>

            </div>

        </div>
    </a>

    @foreach($plants as $plant)
    
    <div data-sku="{{ $plant->sku }}" class="text-center shadow relative bg-white">
    
        <div class="aspect-square relative bg-cover bg-center" style="background-image:url('{{ Storage::disk('public')->url($plant->image.$plant->default_image) }}');"></div>

        <div class="text-center capitalize border-t border-gray-200">

            <div class="relative">

                
                <div class="text-xs p-2 overflow-clip overflow-hidden">{{ $plant->name }}</div>


                <div class="grid grid-cols-2 border-t">
                    
                    <button wire:target="delete_plant" wire:loading.attr="disabled" wire:click="delete_plant('{{$plant->plant_id}}')" class="border-r text-xs py-2 text-red-500 w-full">Delete</button>

                    <a href="{{ url('user/my-plants/'.$plant->id.'/edit') }}">
                        <div class="text-xs py-2">Edit</div>
                    </a>
                </div>


            </div>

        </div>

    </div>
 
    @endforeach

</div>