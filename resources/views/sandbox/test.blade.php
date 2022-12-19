<html>
    <head>
        @livewireStyles
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <wireui:scripts />
        <script src="//unpkg.com/alpinejs" defer></script>
        @livewireScripts
        
    </head>

    <div class="max-w-4xl">
    @livewire('sandbox.select')
    </div>

    
    <x-footer-tag></x-footer-tag>
</html>