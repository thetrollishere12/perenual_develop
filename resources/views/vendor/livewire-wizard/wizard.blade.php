<div>
    <form wire:submit.prevent="save">
        @include('livewire-wizard::steps-header')
        <div>
            <x-errors class="mb-4"/>
            {{ $this->getCurrentStep() }}
        </div>

        @include('livewire-wizard::steps-footer')
    </form>
</div>
