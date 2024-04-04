<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@isset($pageTitle)
            {{ $pageTitle }} | {{ env('APP_NAME') }} | {{ app()->getLocale() }}
        @else
            {{ env('APP_NAME') }} | {{ app()->getLocale() }}
        @endisset</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('front.partials.metatags')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    @yield('styles_url')
    @vite(['resources/css/app.css'])
    @yield('custom_styles')
</head>
<body>
@include('front.partials.header')
@yield('content')
@include('front.partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
@yield('scripts_url')
@yield('custom_scripts')
</body>
</html>
