<div>
    <div>


        <h1 class="font-bold text-2xl py-8">What type of plant are you looking for?</h1>


        <div class="w-full space-y-2">  
            <div class="cat">
                <label>
                   <input type="radio" id="indoor" value="1" wire:model="state.indoor"><span>Indoor</span>
                </label>
             </div>
  


            <div class="cat">
                <label>
                   <input type="radio" id="outdoor" value="0" wire:model="state.indoor"><span>Outdoor</span>
                </label>
            </div> 
            @if($this->state['indoor'] === "0") 
                <div class="mt-4 w-full">
                    <x-jet-label for="location" value="{{ __('Choose Location') }}" />
                    <select wire:model="state.location" class="w-1/2 rounded-md">
                        <option value="">Choose...</option>
                        <option value="nepal">Nepal</option>
                        <option value="china">China</option>
                    </select>
                </div>   
            @endif
        </div>    
    </div> 
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>