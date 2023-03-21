<div class="flex flex-col justify-center items-center">
    <div>
        <x-jet-label for="wateringType" value="{{ __('Choose Place') }}" />
        <input type="radio" id="frequent" value="Frequent" wire:model="state.watering">
        <label for="frequent">Frequent</label><br>
        <input type="radio" id="average" value="Average" wire:model="state.watering">
        <label for="average">Average</label><br>
        <input type="radio" id="minimum" value="Minimum" wire:model="state.watering">
        <label for="minimum">Minimum</label><br>
    </div>    
</div>