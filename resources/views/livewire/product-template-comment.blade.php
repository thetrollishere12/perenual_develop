<section class="bg-white">
    <div class="p-6 mb-6 text-base bg-white rounded-lg">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center">
                <p class="inline-flex items-center mr-3 text-sm text-gray-900">
                    <img class="mr-2 w-6 h-6 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="Michael Gough">Person Name
                </p>
                <p class="text-sm text-gray-600">Feb. 8, 2022</time></p>
            </div>
        </div>
        @if($show_edit==false)
            <p class="text-gray-500">{{$comment->comment}}</p>
        @else
            @error('editComment') <div>{{$message}}</div> @enderror
            <form wire:submit.prevent="updateComment">
                <input type="text" wire:model.defer="editComment">
            </form>
        @endif       
        <div class="flex items-center mt-4 space-x-4">
            <button type="button" wire:click="setChild()"
                class="flex items-center text-sm text-gray-500 hover:underline">
                <svg aria-hidden="true" class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Show {{$this->countReplies()}} Replies
            </button>
            <p>
                {{$this->likes}} Likes
            </p>
            <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="$set('showCommentBox',true)">Reply</button>
            @if(!$comment->productCommentsLikes()->where('user_id',auth()->user()->id)->first())
                <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="like()">Like</button>
            @else
                <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="dislike()">Dislike</button>  
            @endif 
            @if($comment->user_id==auth()->user()->id)
                <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="showEdit()">Edit</button>
                <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="removeComment()">Delete</button>
            @endif    
        </div>
        @if($this->showCommentBox==true)
            @error('postComment') <div>{{$message}}</div> @enderror
            <form class="mt-3" wire:keydown.enter="addComment()">
                <div class="py-2 px-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                    <label for="comment" class="sr-only">Your comment</label>
                    <textarea id="comment" rows="6" 
                        class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                        placeholder="Write a comment..." wire:model.defer="postComment"></textarea>
                </div>
            </form>
        @endif     
        @if(($comment->childs()->count()>0 && ($show_child)))
            {{--child--}}
            <div class="pl-5">
                <livewire:plant-search.comment :product_id="$comment->product_id" :parent_id="$comment->id" key="{{ now() }}">      
            </div>
        @endif
    </div>
</section>
