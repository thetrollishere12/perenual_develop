<div>
    <div>
        <x-jet-label for="indoor_type" value="{{ __('Choose Indoor') }}" />
        <input type="radio" id="indoor" value="1" wire:model="state.indoor">
        <label for="indoor">Indoor</label><br>
        <input type="radio" id="outdoor" value="0" wire:model="state.indoor">
        <label for="outdoor">Outdoor</label><br>
    </div>        
    
    @if($this->state['indoor']==="0") 
        <div class="mt-4">
            <x-jet-label for="location" value="{{ __('Choose Location') }}" />
            <select wire:model="state.location">
                <option value="">Choose...</option>
                <option value="nepal">Nepal</option>
                <option value="china">China</option>
            </select>
        </div>   
    @endif     
</div>