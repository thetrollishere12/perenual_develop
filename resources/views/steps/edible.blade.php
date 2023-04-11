<div class="flex flex-col justify-center items-center">
    <div class="space-y-2">
        <p>Edible</p>
        <div class="cat">
            <label>
               <input type="radio" id="edible_true" value="1" wire:model="state.edible"><span>Yes</span>
            </label>
         </div>
        <div class="cat">
            <label>
               <input type="radio" id="edible_false" value="0" wire:model="state.edible"><span>No</span>
            </label>
         </div>   
    </div>
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>