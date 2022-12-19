<form wire:submit.prevent="submit">
   <h1 class="text-3xl font-bold">Shop Profile</h1>
   <br>
   <div>
      <div class="mx-auto">
         <div>
            @if ($temp_image)
            <img class="h-20 my-2 w-20 object-cover" src="{{ $temp_image->temporaryUrl() }}"/>
            @else
            <x-shop-profile-photo>
               <x-slot name="size">20</x-slot>
            </x-shop-profile-photo>
            @endif
            <div>
               <div>
                  <div class="text-xs pt-1 pb-2">
                     <input wire:target="temp_image" wire:loading.attr="disabled" type="file" wire:model="temp_image" accept="image/*" />
                  </div>
                  @error('$temp_image')
                  <span class="text-danger" style="font-size: 11.5px;">{{ $message }}</span>
                  @enderror
               </div>
            </div>
         </div>
      </div>
   </div>
   <br>
   <x-select
      required
      label="Location"
      placeholder="Choose Country"
      :options="$json"
      wire:model="country"
      option-label="name"
      option-value="code"
      clearable="false"
      icon="location-marker"
      />
   @if(env('LOCAL_PICKUP') == true)
   <x-label wire:loading.class="text-gray-400" class="pt-4 pb-2" wire:target="local_pickup">Allow Local Pickup</x-label>
   <div class="pb-3">
      <x-toggle wire:change="local_pickup" md wire:loading.attr="disabled" wire:target="local_pickup" wire:model="local_pickup"/>
   </div>
   @endif
   @if(env('LOCAL_PICKUP') == true && $local_pickup == true)
   <x-label>Pickup Address</x-label>
   <div class="pt-2 text-xs">
      <input required class="block w-full outline-none p-2 border-t border-x border-b-0 border-gray-200 rounded-t" placeholder="Address Line 1" type="" wire:model.defer="line1" maxlength="100">
      <input class="block w-full outline-none p-2 border-x border-t border-b-0 border-gray-200 text-xs" placeholder="Address Line 2" type="" wire:model.defer="line2" maxlength="100">
      <input class="block w-full outline-none p-2 border-x border-t border-b-0 border-gray-200 text-xs" placeholder="City" type="" required wire:model.defer="city" maxlength="100">
      <select required class="block w-full outline-none p-2 border-x border-b-0 border-gray-200 text-xs" wire:model.defer="state_county_province_region">
         @foreach($spr['states'] as $key => $value)
         <option id="{{ $key }}" value="{{ $value }}">{{ $value }}</option>
         @endforeach
      </select>
      <input class="block w-full outline-none p-2 border border-gray-200 rounded-b-md text-xs" type="" placeholder="Postal Code/Zip Code" required wire:model.defer="postal_zip"  maxlength="100">
   </div>
   @endif
   <x-input wire:model="name" icon="pencil" label="Shop Name" placeholder="Shop Name" minlength="5" maxlength="100" wire:keyup="check" required/>
   <br>
   <x-textarea wire:model.defer="about" maxlength="10000" label="About Your Shop" placeholder="Write what you want your customers to know about your shop!" />
   <br>
   <x-label>Social Media</x-label>
   <div class="md:grid-cols-2 grid gap-2">
      @foreach($this->mediaTypes as $m => $type)
      <div class="relative">
         <div style="top: 0.055rem; left: 0.055rem;" class="icon-{{$type['platform']}}-box inline-block text-white absolute rounded p-2 mr-1"><span class="icon-{{$type['platform']}}"></span></div>
         <input class="text-xs w-full border rounded py-2 pr-2 pl-10" maxlength="500" type="url" placeholder="{{ucfirst($type['platform'])}} URL" wire:model.defer="social.{{$type['platform']}}">
      </div>
      @endforeach
   </div>
   <br>
   <x-select
      required
      label="Return & Exchanges"
      placeholder="Choose Return & Exchanges"
      :options="['30 days return/exchange', '15 days return/exchange', 'Free 30 days return/exchange', 'Free 15 days return/exchange','Exchanges only accepted','No return/exchanges']"
      wire:model.defer="return_exchange"
      />
   <br>
   <x-textarea wire:model.defer="return_exchange_policy" maxlength="2000" label="Return & Exchange Policy" placeholder="Write your return policy" />
   <x-errors class="mt-2"/>
   <x-button type="submit" spinner="submit" primary label="Save & Continue" class="mt-2 w-full" />
   </div>
</form>