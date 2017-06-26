<?php

namespace bnjns\SearchTools;

use Illuminate\Support\ServiceProvider;

class SearchToolsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {
        // Published views
        $this->loadViewsFrom(__DIR__ . 'resources/views', 'search-tools');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/search-tools'),
        ], 'views');
        
        // Published assets
        $this->publishes([
            __DIR__ . '/resources/assets/css' => public_path('css/vendors/search_tools'),
        ], 'styles');
        $this->publishes([
            __DIR__ . '/resources/assets/js/search_tools.min.js' => public_path('js/vendors/search_tools/search_tools.js'),
        ], 'scripts');
    }
    
    /**
     * Register any package services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('search-tools', function ($app) {
            return new SearchTools($app['request'], $app['router']);
        });
    }
}