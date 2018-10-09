<?php

namespace ScoutEngines\Solr;

use Laravel\Scout\EngineManager;
use Illuminate\Support\ServiceProvider;
use Solarium\Client;

class SolrProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        app(EngineManager::class)->extend('solr', function($app) {
            $config = [
                'endpoint' => [
                    'default' => [
                        'host' => config('scout.solr.host'),
                        'port' => config('scout.solr.port'),
                        'path' => config('scout.solr.path'),
                        'core' => config('scout.solr.core'),
                    ]
                ]
            ];

            return new SolrEngine(new Client($config));
        });
    }
}