<div>

    <h1 class="font-bold text-2xl py-8">Do you live in an area where you get alot of sunlight?</h1>

    <div class="w-full space-y-4">

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