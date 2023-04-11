<div class="flex flex-col justify-center items-center"> 
    <div class="space-y-4">
        <p>Choose Sunlight</p>
        <div class="cat">
            <label>
               <input type="radio" id="full" value="full" wire:model="state.sunlight"><span>Full Sun</span>
            </label>
         </div>
        <div class="cat">
            <label>
               <input type="radio" id="part" value="part_shade" wire:model="state.sunlight"><span>Part-Shade</span>
            </label>
        </div>
        <div class="cat">
            <label>
               <input type="radio" id="shade" value="shade" wire:model="state.sunlight"><span>Shade</span>
            </label>
         </div>  
    </div>
    <link rel="stylesheet" href="{{asset('css/radio-to-button.css')}}">     
</div>