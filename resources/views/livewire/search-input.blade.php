<div class="relative">
    <x-errors class="mb-2"/>
    <form wire:submit.prevent="submit">
    <div class="flex">
        
            <input wire:model="search" placeholder="@error('search') {{ $message }} @else Search For Anything @endif" required type="text" name="search" minlength="4" class="border-t border-l border-b w-full border-r-0 border-inherit py-2.5 focus:ring-0 focus:outline-none {{ $class }}" autocomplete="off"><button wire:loading.attr="disabled" wire:target="submit" class="px-3 py-2 main-bg-c main-b-c border text-white"><span class="icon-search"></span></button>

    </div>
    </form>
    @if($queries->count() > 0)
    <div class="absolute border-x border-b z-10 w-full bg-white left-2/4 -translate-x-2/4 text-left" style="top: 100%;">
        
            @foreach($queries as $key => $query)
            <a href="{{ url('marketplace?search='.$query->query.'&c=click&i='.$query->id) }}"><div wire:key="{{ $key }}" class="px-2.5 py-2 cursor-pointer hover:bg-gray-100">{{ $query->query }}</div></a>
            @endforeach
        
    </div>
    @endif

</div>