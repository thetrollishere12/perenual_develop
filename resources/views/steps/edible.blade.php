<div>

    <h1 class="font-bold text-2xl py-8">Plant is somewhat edible?</h1>

    <div class="w-full space-y-4">
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