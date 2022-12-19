<x-app-layout>

    @section('title')
    Plant Marketplace | Search
    @endsection
    @section('description')
    Shop for houseplants, outdoor plants, fruit trees, shrubs, seeds, vegetables, succulent plants, cactuses, cuttings, herbs, microgreens, gardening tools @if(isset($request['search']))& {{ $request['search'] }}@endif
    @endsection
    

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plant Database Search Finder') }}
        </h1>
    </x-slot>
    
    @livewire('plant-search.plant-search-input')

</x-app-layout>