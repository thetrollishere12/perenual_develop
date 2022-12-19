<div>
    @if($notifications->count() > 0)

        <div class="px-3 py-2">
            <x-button wire:click="readAll" class="w-full" primary>Mark All Read</x-button>
        </div>

        @foreach($notifications as $key => $notification)
        <li wire:key="notification-{{$key}}" class="dropdown-item text-xs gap-3 relative">
            <!-- {{ $notification->data['type'] }} -->
    
            @if($notification->data['type'] == "sold")
            <a href="{{ url('user/shop/sold/'.$notification->data['order_number']) }}">
            <div class="w-full flex gap-2 py-2">
                <div class="w-10 relative">
                    <div class="absolute bg-green-600 text-xs text-white text-center w-4 rounded-full -top-1 -right-1">
                        {{ $notification->output->count() }}
                    </div>
                    @if($notification->output->first())
                    <img class="rounded" src="{{ Storage::disk('public')->url($notification->output->first()->product_image.'thumbnail/'.$notification->output->first()->product_default_image) }}">
                    @else
                    <div class="icon-logo-1 text-xl text-center border text-gray-500 rounded"></div>
                    @endif
                </div>
                <div class="capitalize">
                    <div>{{ $notification->output->count() }} Product {{ $notification->data['type'] }} - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</div>
                    <div>{{ $notification->data['order_number'] }}</div>
                </div>
            </div>
            </a>
            @elseif($notification->data['type'] == "shipped")
            <a href="{{ url('user/shop/purchase/'.$notification->data['order_number']) }}">
            <div class="w-full flex gap-2 py-2">
                <div class="w-10"><img class="rounded bg-white p-1" src="{{ Storage::disk('public')->url('image/check.png') }}"></div>
                <div class="capitalize">
                    <div>Shipped - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</div>
                    <div>{{ $notification->data['order_number'] }}</div>
                </div>
            </div>
            </a>
            @elseif($notification->data['type'] == "refund")
            <!-- Error
            <a href="{{ url('user/shop/sold/'.$notification->data['order_number']) }}"> -->

            <a href="{{ url('user/purchases/'.$notification->data['order_number']) }}">
            <div class="w-full flex gap-2 py-2">
                <div class="w-10"><img class="rounded bg-white p-1" src="{{ Storage::disk('public')->url('image/refund.png') }}"></div>
                <div class="capitalize">
                    <div>{{ $notification->data['type'] }} - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</div>
                    <div>{{ $notification->data['order_number'] }}</div>
                </div>
            </div>
            </a>
            @elseif($notification->data['type'] == "cancel_request")
            <a href="{{ url('user/shop/sold/'.$notification->data['order_number']) }}">
            <div class="w-full flex gap-2 py-2">
                <div class="w-10"><img class="rounded bg-white p-1" src="{{ Storage::disk('public')->url('image/pending.png') }}"></div>
                <div class="capitalize">
                    <div>Cancel Request - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</div>
                    <div>{{ $notification->data['order_number'] }}</div>
                </div>
            </div>
            </a>
            @elseif($notification->data['type'] == "cancel")
            <!-- Error
            <a href="{{ url('user/shop/sold/'.$notification->data['order_number']) }}"> -->

            <a href="{{ url('user/purchases/'.$notification->data['order_number']) }}">
            <div class="w-full flex gap-2 py-2">
                <div class="w-10"><img class="rounded bg-white p-1" src="{{ Storage::disk('public')->url('image/cancelled.png') }}"></div>
                <div class="capitalize">
                    <div>Cancelled - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->diffForHumans() }}</div>
                    <div>{{ $notification->data['order_number'] }}</div>
                </div>
            </div>
            </a>
            @endif

            <div wire:click="read('{{ $notification->id }}')" class="icon-close cursor-pointer p-1.5 hover:text-gray-600 absolute top-0 right-0"></div>
            
        </li>
        @endforeach

    @else
        <li href="#" class="dropdown-item text-xs flex gap-3 py-3">
            No Notifications
        </li>
    @endif
</div>