<div class="p-2 flex flex-row-reverse justify-between">
    @if($this->hasNextStep())
        <x-button lg primary  right-icon="chevron-right" wire:click="goToNextStep" spinner="goToNextStep" :label="__('Go To Next')" class="bg-green-500 text-white rounded-sm hover:bg-green-700"/>
    @else
        <x-button lg primary type="submit" spinner="submit" :label="__('Submit')"/>
    @endif
    @if($this->hasPrevStep())
        <x-button lg dark :label="__('Back')" icon="chevron-left" wire:click="goToPrevStep" spinner="goToPrevStep"/>
    @endif
</div>
