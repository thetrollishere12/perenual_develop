<select required wire:model="country" wire:change="country" name="country" class="block w-full outline-none p-2 border-x border-b-0 border-gray-200 rounded-t-md text-xs">
   @foreach(array_column($json,'name') as $key => $value)
   <option id="{{ $key }}" value="{{ $json[$key]['code'] }}">{{ ($json[$key]['name']) }}</option>
   @endforeach
</select>
<input required placeholder="Address Line 1" wire:model.defer="line1" name="line1" maxlength="100" class="block w-full outline-none p-2 border-x border-t border-b-0 border-gray-200 text-xs">
<input placeholder="Address Line 2" wire:model.defer="line2" name="line2" maxlength="100" class="block w-full outline-none p-2 border-x border-t border-b-0 border-gray-200 text-xs">
<input required placeholder="City" wire:model.defer="city" name="city" maxlength="100" class="block w-full outline-none p-2 border-x border-t border-b-0 border-gray-200 text-xs">
<select required wire:model.defer="state_county_province_region" name="state_county_province_region" wire:loading.attr="disabled" wire:loading.class="bg-gray-200" wire:target="country" class="block w-full outline-none p-2 border-x border-b-0 border-gray-200 text-xs">
   @foreach($json[0]['states'] as $key => $value)
   <option id="{{ $key }}" value="{{ $value }}">{{ $value }}</option>
   @endforeach
</select>
<input required placeholder="Postal Code/Zip Code" wire:model.defer="postal_zip" name="postal_zip" maxlength="100" class="block w-full outline-none p-2 border border-gray-200 rounded-b-md text-xs">
<script type="text/javascript">
   var country = "{{ url('storage/json/country.json') }}";
</script>