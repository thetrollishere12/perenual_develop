<div class="flex flex-col justify-center items-center">
    <div>
        <x-jet-label for="edible" value="{{ __('Edible') }}" />
        <input type="radio" id="edible_true" value="1" wire:model="state.edible">
        <label for="edible_true">Yes</label><br>
        <input type="radio" id="edible_false" value="0" wire:model="state.edible">
        <label for="edible_false">No</label><br>
    </div>    
</div>