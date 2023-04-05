<div class="flex flex-col justify-center items-center">
    <div class="space-y-4">
        <x-jet-label for="wateringType" value="{{ __('Choose Place') }}" />
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
            <input type="radio" id="frequent" value="Frequent" wire:model="state.watering" class="hidden">
            <label for="frequent" class="p-2">Frequent</label><br>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">    
            <input type="radio" id="average" value="Average" wire:model="state.watering" class="hidden">
            <label for="average"  class="p-2">Average</label><br>
        </div>
        <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">   
            <input type="radio" id="minimum" value="Minimum" wire:model="state.watering" class="hidden">
            <label for="minimum"  class="p-2">Minimum</label><br>
        </div>    
    </div>
    
    <style>
        .button input[type="radio"]:checked + label {
            background: #20b8be;
            border-radius: 6px;
            width: 100px;
            height: 40px;
            padding: 4px;
            color: white;
        }
    </style>    
</div>