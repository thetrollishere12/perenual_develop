<div>
    <x-errors class="my-2"/>
    @if($shipping->count() > 0)
    <div class="{{ $class }}">
    @foreach($shipping as $s)

       <div class="w-full p-2 grid grid-cols-3 outline-none my-2 text-xs rounded bg-gray-100">

        <div class="col-span-2">
            @if($checkbox != 'false')
            <input class="mr-1" type="radio" required wire:model="shippingMethod" wire:click="$emit('shipping',{{$s['id']}})" value="{{ $s['id'] }}">
            @endif
            <span><b>{{ $s['name'] }}</b></span>
            <div class="pt-0.5">@if($s['cost'] == 0.00) Free shipping @else{{ get_store_currency().$s['cost'] }}@endif domestic, {{ $s['processing'] }} processing time</div>
            <div class="pt-0.5">{{ count($s['international']) }} international shipping @if(count($s['international']) > 1) options @else option @endif</div>
        </div>

        <div class="col-span-1 justify-self-end self-center">
            
            <button class="dropdown-toggle bg-neutral-900 text-xs rounded px-3 py-1 text-white inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition" type="button" data-bs-toggle="dropdown" aria-expanded="true">Edit</button>

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1365px, 79px);" data-popper-placement="bottom-start">
                <li class="dropdown-item text-xs flex cursor-pointer" wire:click="$emit('edit_shipping','{{ $s['id'] }}')">Edit</li>
                <li class="dropdown-item text-xs flex cursor-pointer" wire:click="delete_shipping({{ $s['id'] }});">Delete</li>
            </ul>

        </div>

       </div>

   @endforeach
   </div>
   @endif    
</div>