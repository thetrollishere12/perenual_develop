@section('title')
{{ env('APP_NAME') }} | {{ $user->plant->name }}
@endsection
@section('description')
substr({{ $user->plant->description }},0,160)
@endsection

<x-app-layout>

    <div id="search-container" class="w-full min-h-screen">
        
        <div>
            <div class="max-w-7xl py-2 px-10 mx-auto">
                
                <div class="max-w-3xl relative mx-auto">
                        
                    <a href="{{ url('users/profile/'.$user->id) }}">
                        <div class="top-0 absolute left-8 opacity-80 hover:opacity-100 duration-200 bg-white pt-1 px-2 rounded-b-full inline-block">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button class="text-sm border-2 border-transparent rounded-full focus:outline-none transition">
                                <img class="h-8 my-2 w-8 mx-auto rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                            </button>         
                        @endif
                        </div>
                    </a>


                    <div class="absolute right-0 -translate-y-full w-14" style="top:35%;">
                        
                        <div class="hex text-white relative cursor-pointer w-20">
                            <div class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 icon-bubble text-blue-500 text-3xl"></div>
                        </div>
                        <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
                          <defs>
                                <filter id="goo"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                                    <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
                                </filter>
                            </defs>
                        </svg>

                    </div>

                    <div class="absolute right-0 top-1/2 -translate-y-full w-14">
                        
                        <div class="hex text-white relative cursor-pointer w-20">
                            <div class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 icon-heart1 text-red-500 text-3xl"></div>
                        </div>
                        <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
                          <defs>
                                <filter id="goo"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                                    <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
                                </filter>
                            </defs>
                        </svg>

                    </div>

                    <div class="absolute right-0 -translate-y-full w-14" style="top:65%;">
                        
                        <div class="hex text-white relative cursor-pointer w-20">
                            <div class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 icon-share text-green-500 text-3xl"></div>
                        </div>
                        <svg style="visibility: hidden; position: absolute;" width="0" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1">
                          <defs>
                                <filter id="goo"><feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" />    
                                    <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                                    <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
                                </filter>
                            </defs>
                        </svg>

                    </div>



                    <img class="w-full" src="{{ Storage::disk('public')->url($user->plant->image.$user->plant->default_image) }}">

                    <div class="mx-8 py-4 relative bottom-40 opacity-80 hover:opacity-100 duration-200 bg-white rounded-lg text-black italic text-3xl text-center">{{ $user->plant->name }}

                        <div></div>
                        <div class="main-t-c italic">{{ $user->plant->species }}</div>
                    </div>

                </div>

            </div>
        </div>

    </div>


<style type="text/css">
    
.hex {
  display: inline-block;
  margin:0 5px;
  filter: url('#goo');
}

.hex::before {
  content: "";
  display: block;
  background:currentColor;
  padding-top: 115%;
  clip-path: polygon(0% 25%,0% 75%,50% 100%,100% 75%,100% 25%,50% 0%);
}

</style>

</x-app-layout>