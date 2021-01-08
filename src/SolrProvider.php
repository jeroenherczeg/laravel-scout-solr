<?php

namespace ScoutEngines\Solr;

use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SolrProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        app(EngineManager::class)->extend('solr', function ($app) {
            $config = [
                'endpoint' => [
                    'default' => [
                        'host' => config('scout.solr.host'),
                        'port' => config('scout.solr.port'),
                        'path' => config('scout.solr.path'),
                        'core' => config('scout.solr.core'),
                    ],
                ],
            ];

            $adapter = new Curl();
            $eventDispatcher = new EventDispatcher();
            $client = new Client($adapter,$eventDispatcher,$config);
            if(config('scout.solr.username')){
                $client->getEndpoint()
                    ->setAuthentication(config('scout.solr.username'),config('scout.solr.password'));
            }

            return new SolrEngine($client);
        });
    }
}
