<section class="bg-white">
    <div class="p-6 mb-6 text-base bg-white rounded-lg">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center">
                <p class="inline-flex items-center mr-3 text-sm text-gray-900">
                    <img class="mr-2 w-6 h-6 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="Michael Gough">Person Name
                </p>
                <p class="text-sm text-gray-600 pr-4">{{date('d-m-Y', strtotime($comment->created_at))}}</time></p>
                @if($comment->productReviews()->first()) 
                    @for($i=1; $i<=$comment->productReviews()->first()->ratings; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 fill-gray-300">
                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                        </svg>  
                    @endfor
                    
                    @if($comment->productReviews()->first()->ratings<5)
                        @for($i=1; $i<=5-$comment->productReviews()->first()->ratings; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg> 
                        @endfor 
                    @endif     
                @endif
            </div>
        </div>
        
        @if($show_edit==false)
            <p class="text-gray-500">{{$comment->comment}}</p>
        @else
            @error('editComment') <div>{{$message}}</div> @enderror
            <form wire:keydown.enter="updateComment">
                <textarea id="edit_comment" rows="6" wire:model.defer="editComment" class="w-full text-sm text-gray-900 border-gray-300">
                </textarea>
            </form>
        @endif       
        <div class="flex items-center mt-4 space-x-4">
            @if($this->countReplies()>0)
                <button type="button" wire:click="$toggle('show_child')"
                    class="flex items-center text-sm text-gray-500 hover:underline">
                    <svg aria-hidden="true" class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    @if(!$this->show_child) Show @else Hide @endif{{$this->countReplies()}} Replies
                </button>
            @endif    
            <p class="text-gray-500">
                {{$this->likes}} Likes
            </p>

            @if(Auth::check())
                <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="$set('showCommentBox',true)">Reply</button>
                @if($comment->user_id!=auth()->user()->id && !$comment->productCommentsLikes()->where('user_id',auth()->user()->id)->first())
                    <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="like()">Like</button>
                @elseif($comment->user_id!=auth()->user()->id && $comment->productCommentsLikes()->where('user_id',auth()->user()->id)->first())
                    <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="dislike()">Dislike</button>  
                @endif 
                @if($comment->user_id==auth()->user()->id)
                    <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="showEdit()">Edit</button>
                    <button class="flex items-center text-sm text-gray-500 hover:underline" wire:click="removeComment()">Delete</button>
                @endif
            @endif      
        </div>    
        @if(($comment->childs()->count()>0 && ($show_child)))
            {{--child--}}
            <div class="pl-5">
                <livewire:plant-search.comment :product_id="$comment->product_id" :parent_id="$comment->id" key="{{ now() }}">      
            </div>
        @endif
        @if($this->showCommentBox==true)
            @error('postComment') <div>{{$message}}</div> @enderror
            <form class="mt-3" wire:keydown.enter="addComment()">
                <div class="py-2 px-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                    <label for="comment" class="sr-only">Your comment</label>
                    <textarea id="comment" rows="6" 
                        class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none"
                        placeholder="Write a comment..." wire:model.defer="postComment"></textarea>
                </div>
            </form>
        @endif 
    </div>
</section>
