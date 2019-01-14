<?php

namespace Knowfox\Core;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Knowfox\Core\Models\Concept;
use Knowfox\Core\Models\Item;
use Knowfox\Core\Observers\ConceptObserver;
use Knowfox\Observers\ItemObserver;
use Knowfox\Core\Policies\ConceptPolicy;
use Knowfox\ViewComposers\AlphaIndexComposer;
use Knowfox\ViewComposers\ImpactMapComposer;

class ServiceProvider extends IlluminateServiceProvider
{
    
    protected $policies = [
        Concept::class => ConceptPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'core');

        $this->publishes([
            __DIR__ . '/../knowfox.php' => config_path('knowfox.php'),
            __DIR__ . '/../lang' => resource_path('lang/vendor/knowfox'),
        ]);
        
        Concept::observe(ConceptObserver::class);
        Item::observe(ItemObserver::class);
        View::composer('concept.show-impact-map', ImpactMapComposer::class);
        View::composer('partials.alpha-nav', AlphaIndexComposer::class);

        // Because mpociot/versionable does not specify it
        $this->loadMigrationsFrom(__DIR__ . '/../../vendor/mpociot/versionable/src/migrations');
    }

    protected function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPolicies();
    }
}
