<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=">
    <meta name="google-site-verification" content="{{env('GOOGLE_SITE')}}" />
    <meta name="google-site-verification" content="{{env('GOOGLE_SITE2')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (!empty($uuid))
        <meta name="uuid" content="{{$uuid}}">
    @endif

    <title>@if (!empty($page_title)){{$page_title}} | @endif{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="/img/knowfox-icon.ico">
    <link rel=”icon” type=”image/png” href=”/img/knowfox-icon.png”>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('header_scripts')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body class="{{ str_replace('.', '-', Route::currentRouteName()) }}{{ Route::currentRouteName() != 'home' ? ' not-home' : '' }}">
    @section('navbar')
        @include('core::partials.navbar')
    @show

    @yield('content')

    <footer class="footer">
        <div class="container">
            <p class="text-muted">
                &copy; {{ date('Y') }} Dr. Olav Schettler |
                <a href="javascript:(function(){d=document.createElement('iframe');d.style='position:fixed;z-index:9999;top:10px;right:10px;width:200px;height:200px;background:#FFF;';d.src='{{ config('app.url') }}/bookmark?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title);document.body.appendChild(d);})()"><i class="glyphicon glyphicon-bookmark"></i><span class="desktop-only"> Bookmarklet</span></a>
                | <a href="https://addons.mozilla.org/de/firefox/addon/knowfox/">Firefox Extension</a>
                | <a href="https://blog.knowfox.com">Blog</a>
                | <a href="https://knowfox.com/presentation/47d6c8de/013c/11e7/8a8c/56847afe9799/index.html">Features</a>
                | <a href="https://github.com/oschettler/knowfox/wiki">Getting started</a>
                | <a href="https://github.com/oschettler/knowfox/issues">Open issues</a>
                | <a href="https://github.com/oschettler/knowfox" title="Knowfox is OpenSource. Download it on Github"><img style="height:16px" src="/img/github-32px.png"> OpenSource</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    @yield('footer_scripts')
</body>
</html>
