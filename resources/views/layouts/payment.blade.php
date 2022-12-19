<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta name="robots" content="noindex, nofollow">
    
    @yield('include')
    <x-header-tag>
      <x-slot name="title">@yield('title')</x-slot>
    </x-header-tag>
   
</head>
<body>
    <x-payment-processing></x-payment-processing>
    @yield('main')
    <x-footer-tag></x-footer-tag>
</body>
</html>