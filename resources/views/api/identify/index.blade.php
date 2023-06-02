<x-guest-layout>

    @section('title')
    Free Plant API | Houseplants,Garden,Trees,Flowers,Images & Data
    @endsection
    @section('description')
    Free API with over 10,000 plant species for images & data. Get botanic info on watering,sunlight,growth,pest diseases & more! Easy to intergrate with your apps
    @endsection
    
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plant Identify API Docs') }}
        </h1>
    </x-slot>
    
    <div class="bg-white">
        
    <div class="max-w-7xl mx-auto pt-4 px-2">
        <h1 class="font-bold text-5xl py-3 text-center">Plant API Documentation</h1>
        <div class="text-center">
         <a href="{{ url('user/developer') }}"><x-button primary label="GET API KEY & ACCESS" /></a>
         </div>

         <a class="flex justify-center text items-center text-md mt-3" href='{{ env("SOCIAL_MEDIA_DISCORD") }}'>
            <div class="icon-discord-box rounded-md p-2 mr-2 text-white"><span class="icon-discord"></span></div>
            <p class="inline-block font-bold" style="color: #5865f2;">Join our community of coders, get help & ask questions in a collaborative environment!😄</p>
        </a>

    </div>

    <div class="main-bg-c my-20">

        <div class="max-w-7xl relative mx-auto relative grid md:grid-cols-2 items-center">
            
            <div>
            <h2 class="text-white text-center py-8 text-4xl font-bold">OVER 10,000+ SPECIES OF PLANTS AVAILABLE</h2>
            </div>
            
            <img class="w-full p-12" src="{{ Storage::disk('public')->url('image/plant-api-png.png') }}">
            

        </div>
    </div>

    <div class="max-w-7xl mx-auto px-2">
        
    <h2 class="font-bold text-5xl py-4 text-center">GET BOTANICAL DATA FOR PLANTS</h2>

    <p class="p-4">Our plant/botanic API is a custom-built RESTful API designed to help developers quickly and easily build plant-related services and applications. It provides access to a wealth of plant-based information, including species, care guides, growth stages, images, hardiness zones and much more, making it an ideal choice for developers looking to create plant-related applications and services. Our API also provides many niche botany/botanical resources for varieties like tropical houseplants, sustainable outdoor plants, non-poisonous edible fruits/berries and medical herbs</p>



    <div class="font-bold p-3 flex items-center gap-2">
       <div class="text-3xl">API Status - </div>
       @if(env('API_STATUS') == 'ONLINE')
       <div class="text-2xl flex items-center gap-2">Online<div class="rounded-full w-5 h-5 bg-green-500"></div></div>
       @else
       <div class="text-2xl flex items-center gap-2">Offline<div class="rounded-full w-5 h-5 bg-red-500"></div></div>
       @endif
   </div>

   <div class="font-bold text-3xl p-3">Try Out</div>

    @livewire('api.identify.input')

    <div class="font-bold text-3xl p-3">Plant Identify</div>

    <div class="rounded-md w-full bg-gray-800 text-xs" style="letter-spacing: 0.1em;">
      <pre class="text-white">

    https://{{ $_SERVER['SERVER_NAME'] }}/api/species-list?page=1&key=<a class="text-green-300" href="{{ url('user/developer') }}">{{ $key }}</a>
      </pre>
    </div>

    <div class="my-3 grid md:grid-cols-3 gap-1">

        <div>
            
            <div class="font-bold border-b pb-1">Parameter</div>

            <div class="text-sm">
                
                <div class="py-3 border-b">
                    
                    <div class="font-bold">
                        <span class="italic">Key - </span> <span class="text-green-500">Required</span>
                    </div>

                    <div>A secret/unique number to gain access</div>

                </div>

                <div class="py-3 border-b">
                    
                    <div class="font-bold">
                        <span class="italic">Page - </span> <span class="text-green-500">integer, optional, default is 1</span>
                    </div>

                    <div>The number page you want to see.</div>

                    <div class="text-xs italic mt-2 text-green-500">https://{{ $_SERVER['SERVER_NAME'] }}/api/species-list?key=<a href="{{url('user/developer')}}">[YOUR-API-KEY]</a>&page=3</div>

                </div>

                <div class="py-3 border-b">
                    
                    <div class="font-bold">
                        <span class="italic">q - </span> <span class="text-green-500">optional, string</span>
                    </div>

                    <div>A string/query consisting of keywords that are used to search for names of species</div>

                    <div class="text-xs italic mt-2 text-green-500">https://{{ $_SERVER['SERVER_NAME'] }}/api/species-list?key=<a href="{{url('user/developer')}}">[YOUR-API-KEY]</a>&q=monstera</div>

                </div>

            </div>

        </div>

        <pre class="rounded-md text-xs text-white px-4 bg-gray-800 break-all md:col-span-2" style="letter-spacing: 0.1em;">

<span class="text-gray-400"><span><</span>!-- Example JSON Output --></span>  

<span  class="text-orange-300">
{
    "data": [
        {
            "id": 1,
            "common_name": "European Silver Fir",
            "scientific_name": [
                "Abies alba"
            ],
            "other_name": [
                "Common Silver Fir"
            ],
            "cycle": "Perennial",
            "watering": "Frequent",
            "sunlight": [],
            "default_image": {
                "image_id": 9,
                "license": 5,
                "license_name": "Attribution-ShareAlike License",
                "license_url": "https://creativecommons.org/licenses/by-sa/2.0/",
                "original_url": "https://{{ $_SERVER['SERVER_NAME'] }}/storage/species_image/2_abies_alba_pyramidalis/og/49255769768_df55596553_b.jpg",
                "regular_url": "https://{{ $_SERVER['SERVER_NAME'] }}/storage/species_image/2_abies_alba_pyramidalis/regular/49255769768_df55596553_b.jpg",
                "medium_url": "https://{{ $_SERVER['SERVER_NAME'] }}/storage/species_image/2_abies_alba_pyramidalis/medium/49255769768_df55596553_b.jpg",
                "small_url": "https://{{ $_SERVER['SERVER_NAME'] }}/storage/species_image/2_abies_alba_pyramidalis/small/49255769768_df55596553_b.jpg",
                "thumbnail": "https://{{ $_SERVER['SERVER_NAME'] }}/storage/species_image/2_abies_alba_pyramidalis/thumbnail/49255769768_df55596553_b.jpg"
            }
        }
        ...
    ],
    "to": 30,
    "per_page": 30,
    "current_page": 1,
    "from": 1,
    "last_page": 405,
    "total": 10104
}
</span>

       </pre>

    </div>





    </div>

    </div>

</x-guest-layout>