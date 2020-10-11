<div  class="uk-offcanvas-bar uk-flex uk-flex-column uk-light">
    <button class="uk-offcanvas-close" type="button" uk-close></button>

    <ul class="uk-nav uk-nav-primary uk-margin-auto-vertical">
        @foreach ([
            [
                'prefix' => 'urls',
                'link' => route('concept.index'),
                'title' => 'Concepts'
            ],
/*
            [
                'feature' => 'mindyourteam.feature.product-planning',
                'prefix' => 'productplan',
                'link' => route('product', ['productplan' => 1]),
                'title' => 'Produktplanung 2020'
            ],
*/
        ] as $item)
            @if (!isset($item['feature']) || config($item['feature'], true))
            <li class="{{ Request::is($item['prefix']) || Request::is($item['prefix'] . '/*') ? 'active' : '' }}"><a href="{{ $item['link'] }}">
                {{ $item['title'] }}</a></li>
            @endif
        @endforeach
    </ul>
</div>
