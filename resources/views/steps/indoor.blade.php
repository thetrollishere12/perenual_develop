<div>
    <div class="flex flex-col justify-center items-center">
        <x-jet-label for="indoor_type" value="{{ __('Choose Indoor') }}" />
        <input type="radio" id="indoor" value="1" wire:model="state.indoor" class="mt-2">
        <label for="indoor">Indoor</label><br>
        <img src="https://www.floraqueen.com/blog/wp-content/uploads/2016/03/iStock_000059265530_Medium.jpg" alt="flower" class="w-50">
        <input type="radio" id="outdoor" value="0" wire:model="state.indoor" class="mt-2">
        <label for="outdoor">Outdoor</label><br>
    </div>        
    
    @if($this->state['indoor']==="0") 
        <div class="mt-4 space-y-2">
            <x-jet-label for="location" value="{{ __('Choose Location') }}" />
            <select wire:model="state.location" class="w-full rounded-md">
                <option value="">Choose...</option>
                <option value="nepal">Nepal</option>
                <option value="china">China</option>
            </select>
        </div>   
    @endif

    <div class="mt-4 flex flex-col justify-center items-center">
        <div class="space-y-2 w-1/2">
            <x-jet-label for="description" value="{{ __('Description') }}" />
            <textarea class="w-full rounded-md" rows="4" cols="80" wire:model="state.description"></textarea>
        </div>    
    </div>
</div>