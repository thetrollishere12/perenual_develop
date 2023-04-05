<div class="flex flex-col justify-center items-center">
    <div class="space-y-2">
        <x-jet-label for="fruits" value="{{ __('Fruits') }}" />
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
            <input type="radio" id="fruits_true" value="1" wire:model="state.fruits" class="hidden" >
            <label for="fruits_true" class="p-2">Yes</label>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="fruits_false" value="0" wire:model="state.fruits" class="hidden">
            <label for="fruits_false" class="p-2">No</label>
        </div>    
    </div> 
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">    
</div>