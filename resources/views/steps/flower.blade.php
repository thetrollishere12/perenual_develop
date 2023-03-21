<div class="flex flex-col justify-center items-center">
    <div>
        <x-jet-label for="flower" value="{{ __('Flower') }}" />
        <input type="radio" id="flower_true" value="1" wire:model="state.flower">
        <label for="flower_true">Yes</label><br>
        <input type="radio" id="flower_false" value="0" wire:model="state.flower">
        <label for="flower_false">No</label><br>
    </div>    
</div>