
<nav class="navbar navbar-expand-md navbar-light bg-light mb-4">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
        @if (Auth::check())
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Concepts <span class="caret"></span></a>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="{{route('concept.index')}}">All</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('concept.toplevel')}}">Toplevel</a>
                    <a class="dropdown-item" href="{{route('concept.popular')}}">Popular</a>
                    <a class="dropdown-item" href="{{route('concept.flagged')}}">Flagged</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('tags.cloud')}}">Tags</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('concept.shares')}}">Shared by me</a>
                    <a class="dropdown-item" href="{{route('concept.shared')}}">Shared with me</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('concept.create')}}"><i class="glyphicon glyphicon-plus-sign"></i> New concept</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tasks <span class="caret"></span></a>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="{{route('item.todo')}}">Open</a>
                    <a class="dropdown-item" href="{{route('item.done')}}">Completed</a>
                </div>
            </li>

            <li class="nav-item"><a class="nav-link" href="{{ route('journal') }}"><i class="glyphicon glyphicon-grain"></i> {{ strftime('%Y-%m-%d') }}</a></li>
        @endif
        </ul>

        @if (Auth::check() && Route::currentRouteName() != 'home')
            @include('core::partials.search-form', ['class' => 'desktop-only form-inline navbar-left'])
        @endif

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav navbar-right">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            @else
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" id="generate-token" href="#">API-Token</a>
                        <a class="dropdown-item" href="{{ route('user.passport') }}">Passport</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>
