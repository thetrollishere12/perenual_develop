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
            {{--parent --}}
            <livewire:product-template-comment  key="{{ now() }}" :comment="$item" :product_id="$this->product_id" :show_child="$this->show_child"/>
        @endforeach
    </div>   
</div>
