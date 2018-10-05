<?php

namespace ScoutEngines\Solr;

use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;
use Solarium\Client;

class SolrEngine extends Engine
{
    /**
     * @var Client
     */
    private $client;

    /**
     * SolrEngine constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Update the given model in the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function update($models)
    {
        // TODO: Implement update() method.
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function delete($models)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        // TODO: Implement search() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @param  int $perPage
     * @param  int $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        // TODO: Implement paginate() method.
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        // TODO: Implement mapIds() method.
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @param  mixed $results
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        // TODO: Implement map() method.
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  mixed $results
     * @return int
     */
    public function getTotalCount($results)
    {
        // TODO: Implement getTotalCount() method.
    }
}