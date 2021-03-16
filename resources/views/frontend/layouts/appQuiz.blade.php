<!DOCTYPE html>
@langrtl
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @endlangrtl
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Huwe</title>
        <meta name="description" content="@yield('meta_description', 'Laravel Starter')">
        <meta name="author" content="@yield('meta_author', 'FasTrax Infotech')">
    @yield('meta')

    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;800&display=swap" rel="stylesheet">

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        {{ style(mix('css/frontend.css')) }}

        @stack('after-styles')
    </head>
    <body>
    <div id="app">
        @yield('content')
    </div><!-- #app -->
    @if (Auth::check())
        <meta name="user_id" content="{{ Auth::user()->id }}" />
    @endif
    <!-- Scripts -->
    @stack('before-scripts')
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/frontend.js')) !!}
    @stack('after-scripts')

    </body>
    </html>
