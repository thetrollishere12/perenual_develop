@section('title')
{{ env('APP_NAME') }} - Shop & Sell Plants & Flowers Online | Plant Marketplace
@endsection

@section('description')
{{ env('APP_NAME') }} is an online plant marketplace. Shop for indoor plants, seeds, gardening tools & supplies, trees, bush, fruit & berry trees, scrubs, flowers & more
@endsection

<x-app-layout>
        
    <div class="dashboard-container">
        
        <div class="search-container bg-gray-100 py-10 px-6">
                <div class="max-w-5xl m-auto">
                <livewire:search-input/>
                </div>
        </div>
    </div>


</x-app-layout>