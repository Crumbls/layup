<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <x-layup-seo />
    @stack('head')
</head>
<body>
    {{ $slot }}
    @stack('scripts')
</body></html>
