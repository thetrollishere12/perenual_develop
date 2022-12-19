<div>
                
        @if($displayImage)
        <div class="shadow aspect-square content-center relative bg-contain bg-no-repeat bg-center" style="background-image:url('{{ $displayImage[0]['displayUrl'] }}');">

            <div class="absolute border-t bottom-0 grid grid-cols-3 hidden text-xs w-full">

                <div class="bg-white cursor-pointer py-2 text-center border-r hover:text-gray-500">
                    <span class="icon-crop"></span>
                </div>

                <div class="bg-white cursor-pointer py-2 text-center hover:text-red-500">
                    <span class="icon-close"></span>
                </div>

            </div>

            <div wire:loading wire:target="crop" class="absolute z-10 left-2/4 top-2/4 -translate-x-2/4 -translate-y-2/4">
                <div class="icon-spinner5 text-gray-500 text-center text-3xl rotate"></div>
            </div>

        </div>

        <br>
        @if($plant)
        <form wire:submit.prevent="update">
        @else
        <form wire:submit.prevent="save">
        @endif
        <x-select
            label="Species"
            wire:model.defer="species"
            placeholder="Search For Plant Species"
            :async-data="route('api.species')"
            option-label="name"
            option-value="id"
        />

        <x-input class="mb-2" label="Plant Name" wire:model.defer="name" placeholder="Name of your plant" minlength="1" maxlength="255" required/>

        <x-textarea label="Description" wire:model.defer="description" placeholder="Description for your plant" maxlength="10000" />

        <x-errors class="mt-2" />

        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3">
            <x-button class="w-full" flat label="Redo" wire:click="clear_image" spinner="clear_image" />
            <x-button class="w-full md:col-span-2" type="submit" spinner="save" primary label="Save My Plant" />
        </div>

        </form>

        @else

        <input wire:loading.attr="disabled" type="file" wire:model="image" accept="image/*" id="image-upload" class="hidden" name="image"/>

        <div wire:loading.class="cursor-not-allowed" wire:target="image" class="aspect-square pt-16 upload-case cursor-pointer border-4 border-dashed border-gray-300 rounded-md">
            <img class="m-auto" src="{{ Storage::disk('public')->url('image/download.svg') }}">
            <p class="m-auto text-xs text-center pt-4"><span class="text-blue-600">Select</span> your file(s) from your computer</p>
        </div>

        @endif


    <script type="text/javascript">
        $('.upload-case').click(function(){
            $(this).prev().trigger('click');
        });
    </script>

</div>
