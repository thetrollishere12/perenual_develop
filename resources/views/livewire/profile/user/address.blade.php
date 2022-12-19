<div class="mt-2 w-full md:mt-0 md:col-span-2">

    @if(count($addresses) > 0)

        <div class="shadow rounded p-3">

            <table class="w-full border-spacing-3 border-separate text-sm">
                <tr>
                    <th>NAME</th>
                    <th>ADDRESS</th>
                    <th></th>
                </tr>
                  
                @foreach($addresses as $key => $address)

                    <tr data-pm="{{ $address->id }}">
                        <td>{{ $address->name }} @if($address->default == true) <span class="bg-green-400 rounded text-white px-2 py-1 ml-2 text-xs"><span class="icon-star-full mr-1"></span>Default</span> @endif</td>
                        <td>
                            <div>{{ $address->line1 }}</div>
                            <div>{{ $address->city.' '.$address->state_county_province_region.' '.$address->postal_zip }}</div>
                            <div>{{ country_code_to_string($address->country) }}</div>
                            <div>{{ $address->line2 }}</div>
                        </td>
                            
                        <td>
                            @if($address->default == false)
                            <button class="dropdown-toggle main-bg-c text-xs rounded px-3 py-1 text-white inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="true">Edit</button>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1365px, 79px);" data-popper-placement="bottom-start">

                                <form action="{{ url('user/address/'.$address->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="dropdown-item text-xs flex cursor-pointer" type="submit">Make Default</button>
                                </form>

                                <form action="{{ url('user/address/'.$address->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-xs flex cursor-pointer" type="submit">Delete</button>
                                </form>
                      
                            </ul>
                            @endif
                        </td>
                    </tr>
           
                @endforeach

            </table>

        </div>

    @endif

    <x-modal.card title="Add Address" padding="p-4" blur wire:model.defer="myModal">
        <x-errors class="mb-2"/>
        <div>
          <div class="mb-1"><b>Contact Information</b></div>
           <input class="block w-full outline-none p-2 border text-xs border-gray-200 rounded-md" @auth value="{{ Auth::user()->name }}" @endauth type="name" placeholder="Full Name" require wire:model="name" maxlength="100">
           <div class="my-1"><b>Shipping Address</b></div>
           <x-billing-address></x-billing-address>
        </div>
     
        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">            
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button primary label="Add Address" spinner="submit" wire:click="submit"/>
            </div>
        </x-slot>
    </x-modal.card>

</div>