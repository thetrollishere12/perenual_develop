<div>
    {{-- <div>
            
        <x-textarea wire:model="comment" label="Leave Comment" placeholder="Add a tip or comment for this plant" />

        <x-button wire:click="save" spinner="save" primary label="Submit" />

    </div> --}}

    <div>
        @if($this->parent_id==NULL)
            <form class="flex flex-col mb-6 w-1/2" wire:submit.prevent="addComment">
                <label>Add Comment</label>
                <input type="text" placeholder="Add Comment And Press Enter" wire:model.defer="comment">
            </form>
        @endif
        @foreach ($comments as $item)
            <livewire:product-parent-comment :comment="$item"  key="{{ now() }}" :product_id="$this->product_id" />
        @endforeach
    </div>   
</div>
