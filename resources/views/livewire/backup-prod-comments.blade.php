{{-- <div>
    @foreach ($comments as $item)

    <div class="grid grid-cols-1 mb-5 ml-5">
        <div class="grid grid-cols-1">
            <div class="w-full flex items-center">
                    <img class="mr-3 md:ml-0 w-10" src="https://i.ibb.co/NsGNgTq/Mask-Group.png" alt="avatar"  />
                    Person Name
            </div>
            <div class="flex justify-start flex-col w-full items-start text-left ">
                <p class="text-base first-letter:dark:text-gray-200 text-gray-600 ">{{$item->comment}}</p>
                <p class="text-base font-medium leading-6 dark:text-white text-gray-800 cursor-pointer" wire:click="$set('show_child',true)">{{$item->childs()->count()}} Replies</p>
                @if($item->childs()->count()>0 && $show_child)
                    <div class="pl-5">
                        {{rand(0,1000)}}
                        <livewire:prod-comments :product_id="$product_id" :parent_id="$item->id"/>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div> --}}

<div>
    @foreach ($comments as $item)
        {{-- <section class="bg-white">
            <div class="p-6 mb-6 text-base bg-white rounded-lg">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <p class="inline-flex items-center mr-3 text-sm text-gray-900">
                            <img class="mr-2 w-6 h-6 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-2.jpg" alt="Michael Gough">Person Name
                        </p>
                        <p class="text-sm text-gray-600">Feb. 8, 2022</time></p>
                    </div>
                </div>
                <p class="text-gray-500">{{$item->comment}}</p>
                <div class="flex items-center mt-4 space-x-4">
                    <button type="button" wire:click="$set('show_child',true)"
                        class="flex items-center text-sm text-gray-500 hover:underline">
                        <svg aria-hidden="true" class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Show {{$item->childs()->count()}} Replies
                    </button>
                    <button class="flex items-center text-sm text-gray-500 hover:underline">Reply</button>
                </div>
                <form class="mt-3">
                    <div class="py-2 px-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                        <label for="comment" class="sr-only">Your comment</label>
                        <textarea id="comment" rows="6"
                            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                            placeholder="Write a comment..." required></textarea>
                    </div>
                </form>
            </div>
            @if($item->childs()->count()>0 && $show_child)
                <livewire:prod-comments  :product_id="$product_id" :parent_id="$item->id"/>
            @endif
        </section> --}}
        <livewire:product-parent-comment :comment="$item"/>
    @endforeach
</div>   