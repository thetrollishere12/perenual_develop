<div class="flex flex-col justify-center items-center">
    <div class="space-y-2">
        <x-jet-label for="edible" value="{{ __('Edible') }}" />
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
            <input type="radio" id="edible_true" value="1" wire:model="state.edible" class="hidden">
            <label for="edible_true" class="p-2">Yes</label>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="edible_false" value="0" wire:model="state.edible" class="hidden">
            <label for="edible_false" class="p-2">No</label>
        </div>    
    </div>
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>