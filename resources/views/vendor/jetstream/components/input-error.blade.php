@props(['for'])

@error($for)
    <p x-init="() => { var first = document.getElementsByClassName('validation-error').item(0); window.scroll({
        top: first.getBoundingClientRect().top,
        behavior: 'smooth',
    })  }" {{ $attributes->merge(['class' => 'validation-error text-sm text-red-600']) }}>{{ $message }}</p>
@enderror
