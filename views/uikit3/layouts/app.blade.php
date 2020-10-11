<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Knowfox') }}</title>

    @include("core::{$theme}.partials.favicon")

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
    .uk-offcanvas-bar ul {
    }
    .uk-offcanvas-bar li.active {
        border-left: solid 6px #87788E;
        padding-left: 10px;
        margin-left: -20px;
        font-weight: bold;
    }
    </style>

    @stack('head')
</head>
<body>
<div id="app">
    <div class="uk-background-primary uk-light">
        @include("core::{$theme}.partials.navbar")
    </div>

    <div id="menu" uk-offcanvas="overlay: true">
        @include("core::{$theme}.partials.menu")
    </div>

    <main uk-height-viewport="expand: true">
        @yield('content')
    </main>

    <footer class="uk-section uk-section-xsmall uk-section-secondary">
        <div class="uk-container">
            <div class="uk-grid uk-text-center uk-text-left@s uk-flex-middle" data-uk-grid>
                <div class="uk-text-small uk-text-muted uk-text-right uk-width-1-1">
                    Made with ❤ by
                    <a target="_blank" href="{{ env('APP_SIGNATURE') }}">Olav Schettler</a>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@yield('footer')
@stack('toes')
</body>
</html>
