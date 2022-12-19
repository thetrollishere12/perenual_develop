<div wire:poll>
@if($count > 0)
<div class="absolute bg-red-700 text-xs text-white w-4 rounded-full -top-1 -right-2 text-center">{{ $count }}</div>
@endif
</div>