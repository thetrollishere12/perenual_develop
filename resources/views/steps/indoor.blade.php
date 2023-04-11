<div class="container">
    <div class="grid place-items-center">
        <div class="w-1/2 space-y-2">  
            <div class="cat">
                <label>
                   <input type="radio" id="indoor" value="1" wire:model="state.indoor"><span>Indoor</span>
                </label>
             </div>
            <div>     
                <img src="https://www.floraqueen.com/blog/wp-content/uploads/2016/03/iStock_000059265530_Medium.jpg" alt="flower">
            </div>    
            <div class="space-y-2">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <textarea class="rounded-md w-full" rows="4" cols="80" wire:model="state.description"></textarea>
            </div>
            <div class="cat">
                <label>
                   <input type="radio" id="outdoor" value="0" wire:model="state.indoor"><span>Outdoor</span>
                </label>
            </div> 
            @if($this->state['indoor']==="0") 
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