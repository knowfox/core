<nav class="uk-navbar-container uk-navbar-transparent">
    <div class="uk-container">
        <div class="uk-navbar" data-uk-navbar>
            <div class="uk-navbar-left">
                <button class="uk-navbar-toggle" uk-toggle="target: #menu" uk-navbar-toggle-icon type="button"></button>
                <a class="uk-navbar-item uk-logo" href="/">{{ config('app.name', 'Laravel') }}</a>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li>
                                <a href="{{ route('login') }}">{{ __('Log In') }}</a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li>
                                <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li>
                            <a href="#">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="uk-navbar-dropdown">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Log Out') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>
