<?php

namespace Obelaw\Trail;

use Illuminate\Support\ServiceProvider;
use Obelaw\Trail\Http\Middleware\CaptureRequestContext;
use Obelaw\Twist\Addons\AddonsPool;
use Obelaw\Trail\Observers\TrailObserver;
use Illuminate\Database\Eloquent\Model;

class TrailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/trail.php', 'trail');

        $this->app->singleton('obelaw.trail.logger', function ($app) {
            return new \Obelaw\Trail\TrailLogger;
        });
    }

    public function boot(): void
    {
        AddonsPool::loadTwist(__DIR__ . '/../twist.php');

        // Register middleware through the Router for both web & api groups
        if ($this->app->bound('router')) {
            /** @var \Illuminate\Routing\Router $router */
            $router = $this->app->make(\Illuminate\Routing\Router::class);
            // Optional alias if consumers want to use it by name
            $router->aliasMiddleware('trail.capture', CaptureRequestContext::class);
            // Attach to common groups
            $router->pushMiddlewareToGroup('web', CaptureRequestContext::class);
            $router->pushMiddlewareToGroup('api', CaptureRequestContext::class);
        }

        $this->publishes([
            __DIR__ . '/../config/trail.php' => config_path('trail.php'),
        ], 'trail-config');

        // Publish migrations (for non-Twist consumers) and provide path for loading
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'trail-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Obelaw\Trail\Console\PruneTrailsCommand::class,
            ]);
        }

        // Attach observer to watched models if configured
        $watch = (array) config('trail.watch', []);
        foreach ($watch as $class) {
            if (is_string($class) && class_exists($class) && is_subclass_of($class, Model::class)) {
                $class::observe($this->app->make(TrailObserver::class));
            }
        }
    }
}
