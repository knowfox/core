<?php

namespace Knowfox\Core;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Barryvdh\Cors\HandleCors;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Knowfox\Core\Models\Concept;
use Knowfox\Core\Policies\ConceptPolicy;

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
                'auth:api',
                'throttle:60,1',
                HandleCors::class,
                SubstituteBindings::class,
            ])
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->publishes([
            __DIR__ . '/../knowfox.php' => config_path('knowfox.php'),
        ]);
    }
}
