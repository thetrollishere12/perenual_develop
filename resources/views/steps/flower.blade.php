<div class="flex flex-col justify-center items-center">
    <div class="space-y-4">
        <x-jet-label for="flower" value="{{ __('Flower') }}" />
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
            <input type="radio" id="flower_true" value="1" wire:model="state.flower" class="hidden">
            <label for="flower_true" class="p-2">Yes</label>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="flower_false" value="0" wire:model="state.flower" class="hidden">
            <label for="flower_false" class="p-2">No</label>
        </div>    
    </div>  
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">   
</div>