<?php

namespace Knowfox\Core;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

use Knowfox\Core\Models\Concept;
use Knowfox\Core\Models\Item;
use Knowfox\Core\Observers\ConceptObserver;
use Knowfox\Core\Observers\ItemObserver;
use Knowfox\Core\Policies\ConceptPolicy;
use Knowfox\Core\ViewComposers\AlphaIndexComposer;
use Knowfox\Core\ViewComposers\ImpactMapComposer;

class ServiceProvider extends IlluminateServiceProvider
{
    protected $namespace = 'Knowfox\Core\Http\Controllers';

    protected $policies = [
        Concept::class => ConceptPolicy::class,
    ];

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');
        $this->mapWebRoutes();
        $this->mapApiRoutes();
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'core');

        $this->publishes([
            __DIR__ . '/../knowfox.php' => config_path('knowfox.php'),
            __DIR__ . '/../lang' => resource_path('lang/vendor/knowfox'),
        ]);
        
        Concept::observe(ConceptObserver::class);
        Item::observe(ItemObserver::class);
        View::composer('core::concept.show-impact-map', ImpactMapComposer::class);
        View::composer('core::partials.alpha-nav', AlphaIndexComposer::class);

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
