@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>

        <div class="text-base p-3 border-b font-bold">
            {{ $title }}
        </div>

        <div class="m-3">
            {{ $content }}
            
            @if(isset($footer))
            <div>{{ $footer }}</div>
            @endif
        </div>
    
</x-jet-modal>
