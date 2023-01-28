<x-guest-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Developer') }}
        </h2>
    </x-slot>

    <div class="md:flex">

        <div class="w-full border-l border-gray-200 p-4">

        <x-jet-validation-errors class="mb-4" />
        <x-ValidationSuccess class="my-2" />

        <div>
           <div class="md:col-span-1 flex justify-between">
              <div class="p-1">
                 <h3 class="text-lg font-medium text-gray-900">Developer Details</h3>
                 <p class="mt-1 text-sm text-gray-600">
                    Set up to gain access to a key and our REST API
                 </p>
              </div>
              <div class="p-1">
              </div>
           </div>
           
           @livewire('profile.user.developer')

        </div>

        </div>
    </div>



</x-guest-layout>
