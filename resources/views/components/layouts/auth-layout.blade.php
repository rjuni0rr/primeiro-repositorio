<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} {!! empty($subtitle) ? '' : ' &vellip; ' . $subtitle !!}</title>
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-zinc-200">
    {{--  user top bar  --}}
    <x-layouts.user_top_bar/>

    {{--  main horizontal menu  --}}
    <x-layouts.main_menu/>

    {{--  main content  --}}
    <div class="p-8">
        {{ $slot }}
    </div>


</body>
</html>
