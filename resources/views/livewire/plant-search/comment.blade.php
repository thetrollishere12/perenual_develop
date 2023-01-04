<div>
    {{-- <div>
            
        <x-textarea wire:model="comment" label="Leave Comment" placeholder="Add a tip or comment for this plant" />

        <x-button wire:click="save" spinner="save" primary label="Submit" />

    </div> --}}

    <div>
        @if(!$review_check)
            <form wire:keydown.enter="addCommentRatings">
                @error('ratings') <div>{{$message}}</div> @enderror
                <div class="flex flex-col mb-3 gap-y-2">
                    <label for="">Ratings</label>
                    <input type="text" placeholder="Enter Ratings" class="w-1/2 rounded-lg" wire:model.defer="ratings">
                </div>  
                @error('second_comment') <div>{{$message}}</div> @enderror 
                <div class="flex flex-col mb-3">
                    <label for="">Second Comment</label>
                    <input type="text" placeholder="Enter Second" class="w-1/2 rounded-lg" wire:model.defer="second_comment" placeholder="Comment And Enter">
                </div> 
            </form>
        @endif    
    </div>

    <div>
        @if($this->parent_id==NULL)
            @error('comment') <div>{{$message}}</div> @enderror
            <form class="flex flex-col mb-6 w-1/2" wire:submit.prevent="addComment">
                <label>Add Comment</label>
                <input type="text" placeholder="Add Comment And Press Enter" wire:model.defer="comment">
            </form>
        @endif
        @foreach ($comments as $item)
            {{--parent --}}
            <livewire:product-template-comment :comment="$item" :product_id="$this->product_id" :wire:key="'item-'.$item->id"/>
        @endforeach
    </div>   
</div>
