@if ($errors->any())
    <div x-init="() => { var first = document.getElementsByClassName('validation-error').item(0); window.scroll({
        top: first.getBoundingClientRect().top,
        behavior: 'smooth',
    })  }" {{ $attributes }}>
        <div class="validation-error font-medium text-red-600 text-sm">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="mt-1 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
