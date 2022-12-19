<link rel="canonical" href="{{url()->full()}}">
<meta property="og:url" content="{{url()->full()}}">
@if(isset($title))
<title>{{ $title }}</title>
<meta property="og:title" content="{{ $title }}">
<meta name="twitter:title" content="{{ $title }}">
@endif

@if(isset($description))
<meta name="description" content="{{ $description }}">
<meta property="og:description" content="{{ $description }}">
<meta name="twitter:description" content="{{ $description }}">
@endif

<meta name="author" content="Brandon Huynh">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta property="og:type" content="website" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="content-language" content="en-us">
<meta http-equiv="Cache-control" content="public">


<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

<!-- Styles -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/general.css?'.time().'') }}">
@livewireStyles

<!-- Script -->
@wireUiScripts

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <script type="text/javascript" src="{{ asset('js/general.js?'.time().'') }}"></script> -->


<x-popup-status></x-popup-status>
<x-search-modal></x-search-modal>

<x-notifications z-index="z-50" />
<x-dialog z-index="z-50" blur="md" align="center" />
