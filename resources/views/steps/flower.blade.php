<div class="flex flex-col justify-center items-center">
    <div class="space-y-4">
        <p>Choose Flower...</p>
        <div class="cat">
            <label>
               <input type="radio" id="flower_true" value="1" wire:model="state.flower"><span>Yes</span>
            </label>
        </div>
        <div class="cat">
            <label>
               <input type="radio" id="flower_false" value="0" wire:model="state.flower"><span>No</span>
            </label>
         </div>
    </div>  
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">   
</div>