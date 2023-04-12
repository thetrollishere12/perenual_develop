<div>

    <h1 class="font-bold text-2xl py-8">Are you a person that has time to water?</h1>

    <div class="w-full space-y-4">
        <div class="cat">
            <label>
               <input type="radio" id="frequent" value="Frequent" wire:model="state.watering"><span>Frequent</span>
            </label>
         </div>
        <div class="cat">
            <label>
               <input type="radio" id="average" value="Averafe" wire:model="state.watering"><span>Average</span>
            </label>
         </div>
        <div class="cat">
            <label>
               <input type="radio" id="minimum" value="Minimum" wire:model="state.watering"><span>Minimum</span>
            </label>
         </div>
    </div>
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}"> 
</div>