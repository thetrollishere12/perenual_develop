<div>

    <h1 class="font-bold text-2xl py-8">You like a rare species?</h1>

    <div class="w-full space-y-4">
        <div class="cat">
            <label>
               <input type="radio" id="rare_true" value="1" wire:model="state.rare"><span>Yes</span>
            </label>
         </div>
        <div class="cat">
            <label>
               <input type="radio" id="rare_false" value="0" wire:model="state.rare"><span>No</span>
            </label>
         </div>    
    </div>  
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>