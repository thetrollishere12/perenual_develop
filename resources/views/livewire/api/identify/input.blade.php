    <div>

        <input wire:loading.attr="disabled" type="file" wire:model="image" accept="image/*" id="image-upload"
            class="hidden" name="image" />

        <div wire:loading.class="cursor-not-allowed" wire:target="image"
            class="upload-case cursor-pointer border-4 border-dashed border-gray-300 rounded-md">
            <img class="m-auto p-2" src="{{ Storage::disk('public')->url('image/download.svg') }}">
            <x-jet-validation-errors class="my-2 text-center" />
            <p class="m-auto text-xs text-center pb-10 pt-4"><span class="text-blue-600">Select</span> your file(s) from
                your computer</p>
        </div>

        <!-- Display the array content -->
        <div>
            @if (!empty($summary))
                <p>Common Name: {{ $summary['common_name'] }}</p>
                <p>Scientific Name: {{ $summary['scientific_name'] }}</p>
                <p>Probability: {{ $summary['probability'] }}</p>
            @endif
        </div>

    </div>

    <script type="text/javascript">
        $('.upload-case').click(function() {
            $(this).prev().trigger('click');
        });
    </script>
