@if (session('success'))
    <div {{ $attributes }}>
        <div class="text-white bg-green-400 pl-4 py-2 rounded">{{session('success')}}</div>
    </div>
@endif
