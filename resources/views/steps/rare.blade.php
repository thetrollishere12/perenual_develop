<div class="flex flex-col justify-center items-center">
    <div class="space-y-2">
        <x-jet-label for="rare" value="{{ __('Rare') }}" />
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
            <input type="radio" id="rare_true" value="1" wire:model="state.rare" class="hidden">
            <label for="rare_true" class="p-2">Yes</label>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="rare_false" value="0" wire:model="state.rare" class="hidden">
            <label for="rare_false" class="p-2">No</label>
        </div>    
    </div>  
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>