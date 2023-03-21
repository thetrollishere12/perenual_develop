<div class="flex flex-col justify-center items-center">
    <div>
        <x-jet-label for="fruits" value="{{ __('Fruits') }}" />
        <input type="radio" id="fruits_true" value="1" wire:model="state.fruits">
        <label for="fruits_true">Yes</label><br>
        <input type="radio" id="fruits_false" value="0" wire:model="state.fruits">
        <label for="fruits_false">No</label><br>
    </div>    
</div>