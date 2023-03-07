<div>

    <div class="font-bold p-2 text-2xl">User Credential Key</div>

    <div>
        @if($credential)
        <div class="p-2 bg-white border rounded grid grid-cols-2">
            
            <div>{{ substr($credential->key,0,3) }}........{{ substr($credential->key, -4) }}</div>

            <div class="text-right">Created On {{ Carbon\Carbon::parse($credential->created_at)->format('M d, Y') }}</div>

        </div>      
        @endif

        <x-button wire:click="request" class="mt-2 w-full" spinner="request" primary label="Generate New Key" />

    </div>

</div>