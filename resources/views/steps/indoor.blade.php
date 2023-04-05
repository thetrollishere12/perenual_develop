<div class="container">
    <div class="grid place-items-center">
        <div class="w-1/2 space-y-2">  
            <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">
                <input type="radio" id="indoor" value="1" wire:model="state.indoor" class="hidden">
                <label for="indoor" class="p-2">Indoor</label> 
            </div>
            <div>     
                <img src="https://www.floraqueen.com/blog/wp-content/uploads/2016/03/iStock_000059265530_Medium.jpg" alt="flower">
            </div>    
            <div class="space-y-2">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <textarea class="rounded-md w-full" rows="4" cols="80" wire:model="state.description"></textarea>
            </div>
            <div class="button w-24 bg-gray-500 text-white rounded-md cursor-pointer">   
                <input type="radio" id="outdoor" value="0" wire:model="state.indoor" class="hidden">
                <label for="outdoor" class="p-2">Outdoor</label>
            </div>  
            @if($this->state['indoor']==="0") 
                <div class="mt-4 space-y-2">
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

    <style>
        .button input[type="radio"]:checked + label {
            background: #20b8be;
            border-radius: 6px;
            width: 100px;
            height: 40px;
            padding: 4px;
            color: white;
        }
    </style>
</div>