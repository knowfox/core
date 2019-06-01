<?php

namespace Knowfox\Core;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

use Knowfox\Core\Models\Concept;
use Knowfox\Core\Models\Item;
use Knowfox\Core\Observers\ConceptObserver;
use Knowfox\Core\Policies\ConceptPolicy;
use Knowfox\Core\ViewComposers\AlphaIndexComposer;
use Knowfox\Core\Listeners\AuthListener;
use Knowfox\Core\Listeners\NewUserListener;

class ServiceProvider extends IlluminateServiceProvider
{
    protected $namespace = 'Knowfox\Core\Http\Controllers';

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
        //$this->mapApiRoutes();
        //$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'core');

        $this->publishes([
            __DIR__ . '/../knowfox.php' => config_path('knowfox.php'),
            __DIR__ . '/../public/img/background.jpg' => public_path('img/background.jpg'),
            __DIR__ . '/../public/img/github-32px.png' => public_path('img/github-32px.png'),
            __DIR__ . '/../public/img/knowfox-icon.ico' => public_path('img/knowfox-icon.ico'),
            __DIR__ . '/../public/img/knowfox-icon.png' => public_path('img/knowfox-icon.png'),
            //__DIR__ . '/../lang' => resource_path('lang/vendor/knowfox'),
        ]);
        
        Concept::observe(ConceptObserver::class);

        View::composer('core::partials.alpha-nav', AlphaIndexComposer::class);

        Event::listen(Authenticated::class, AuthListener::class);
        Event::listen(Registered::class, NewUserListener::class);

        // Because mpociot/versionable does not specify it
        // @TODO Does this even work?
        $this->loadMigrationsFrom(__DIR__ . '/../../vendor/mpociot/versionable/src/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Gate::policy(Concept::class, ConceptPolicy::class);
        
        $this->mergeConfigFrom(
            __DIR__ . '/../config/knowfox.php', 'knowfox'
        );
    }
}
