@section('title')
Result
@endsection

<x-app-layout>
    <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <h1 class="text-lg font-bold">Results</h1>
        @if($species)
            <div class="flex flex-col gap-2 mt-4">
                <div>
                    <p>ID</p>
                    <span>{{$species->id}}</span>
                </div>
                <div>
                    <p>Common Namee</p>
                    <span>{{$species->common_name}}</span>
                </div>  
            </div>
        @else
            <div>
                <p class="text-red-500">* No Species Result Found</p>
            </div>
        @endif        
    </div>
</x-app-layout>