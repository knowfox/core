<?php

namespace Knowfox\Core;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Barryvdh\Cors\HandleCors;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Knowfox\Core\Models\Concept;
use Knowfox\Core\Policies\ConceptPolicy;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class ServiceProvider extends IlluminateServiceProvider
{
    protected $namespace = '\Knowfox\Core\Controllers';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../knowfox.php', 'knowfox'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::policy(Concept::class, ConceptPolicy::class);

        //Route::model('concept', Concept::class);

        Route::prefix('api')
            ->middleware([
                'auth:sanctum',
                EnsureFrontendRequestsAreStateful::class,
                'throttle:60,1',
                HandleCors::class,
                SubstituteBindings::class,
            ])
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/api.php');

        Route::prefix('core')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->loadViewsFrom(__DIR__ . '/../views', 'core');

        /*
         * mpociot/versionable does not automatically install
         * its migrations so we do it for them here
         */
        $this->loadMigrationsFrom(__DIR__ .
            '/../../../../vendor/mpociot/versionable/src/migrations');


        $this->publishes([
            __DIR__ . '/../knowfox.php' => config_path('knowfox.php'),
        ]);
    }
}
