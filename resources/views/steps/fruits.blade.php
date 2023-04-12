<div>

    <h1 class="font-bold text-2xl py-8">Do you like fruits?</h1>

    <div class="w-full space-y-4">

        <div class="cat">
            <label>
               <input type="radio" id="fruits_true" value="1" wire:model="state.fruits"><span>Yes</span>
            </label>
         </div>
        <div class="cat">
            <label>
               <input type="radio" id="fruits_false" value="0" wire:model="state.fruits"><span>No</span>
            </label>
         </div>   
    </div> 
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">    
</div>