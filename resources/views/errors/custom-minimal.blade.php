<x-guest-layout>

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <img class="m-auto w-52 py-6 md:pb-16" src="{{ Storage::disk('public')->url('image/error_asset/plant.png') }}">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">

                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    @yield('code')
                </div>

                <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                    @yield('message')
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>