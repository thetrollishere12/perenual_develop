<div class="flex flex-col justify-center items-center">
    <div>
        <x-jet-label for="rare" value="{{ __('Rare') }}" />
        <input type="radio" id="rare_true" value="1" wire:model="state.rare">
        <label for="rare_true">Yes</label><br>
        <input type="radio" id="rare_false" value="0" wire:model="state.rare">
        <label for="rare_false">No</label><br>
    </div>    
</div>