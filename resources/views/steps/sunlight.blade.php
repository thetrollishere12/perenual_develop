<div class="flex flex-col justify-center items-center"> 
    <div class="space-y-4">
        <x-jet-label for="sunlight" value="{{ __('Choose Sunlight') }}" />
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
            <input type="radio" id="full" value="full" wire:model="state.sunlight" class="hidden">
            <label for="full" class="p-2">Full Sun</label>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="part" value="part_shade" wire:model="state.sunlight" class="hidden">
            <label for="part" class="p-2">Part-Shade</label>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="shade" value="shade" wire:model="state.sunlight" class="hidden">
            <label for="shade" class="p-2">Shade</label>
        </div>    
    </div>
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>