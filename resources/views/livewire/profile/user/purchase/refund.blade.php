<x-modal.card title="Leave Review" padding="p-4" blur wire:model.defer="myModal">
    @if($order)
    <div>
        
        
        @foreach($order['order_product'] as $key => $product)
        <div>

            <div class="grid md:grid-cols-4 gap-2">
            <img class="w-1/2 md:w-full rounded" src="{{ Storage::disk('public')->url($product['product_image'].$product['product_default_image']) }}">

            <div class="col-span-2 md:col-span-3 text-left">
                <div class="font-bold text-sm">{{ $product['name'] }}</div>

                <div class="w-full flex gap-2 pb-2">
                    @for($i=1; $i <= 5; $i++)
                    <div wire:click="stars({{$i}},{{$key}})" id="{{$i}}" class="@if($i <= $product['stars']) select-star @endif review-stars text-3xl cursor-pointer text-zinc-300 hover:text-yellow-200">★</div>
                    @endfor
                </div>
                <x-textarea wire:model.defer="order.order_product.{{$key}}.comment" label="" class="h-20" placeholder="Enter your feedback on the product or seller" />
            </div>

            </div>

          <!--   <div>
                <input data-preview="#preview" name="input_img" type="file" id="imageInput">
            </div> -->
            
        </div>
        @endforeach
        

        <x-errors class="mt-2"/>

    </div>
 
    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">            
            <x-button flat label="Cancel" x-on:click="close" />
            @if(count($order['order_product']) > 0)
            <x-button wire:loading.attr="disabled" primary label="Submit Review" spinner="submit" wire:click="submit" />
            @else
            <x-button negative label="Already Submitted"/>
            @endif
        </div>
    </x-slot>
    @endif
</x-modal.card>


<style type="text/css">

.hover-star {
    color: rgb(250 204 21 / var(--tw-text-opacity))
}

.select-star {
    color: rgb(250 204 21 / var(--tw-text-opacity))
}

</style>