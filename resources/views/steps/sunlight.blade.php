<div>
    <x-jet-label for="sunlight" value="{{ __('Choose Sunlight') }}" />
    <input type="radio" id="full" value="full" wire:model="state.sunlight">
    <label for="full">Full Sun</label><br>
    <input type="radio" id="part" value="part_shade" wire:model="state.sunlight">
    <label for="part">Part-Shade</label><br>
    <input type="radio" id="shade" value="shade" wire:model="state.sunlight">
    <label for="shade">Shade</label><br>
</div>