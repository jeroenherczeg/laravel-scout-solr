<?php

declare(strict_types=1);

namespace ScoutEngines\Solr;

use Illuminate\Database\Eloquent\Collection;
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
        if ($models->isEmpty()) {
            return;
        }

        $updateQuery = $this->client->createUpdate();

        $models->each(function($model) use(&$updateQuery){

            $searchableModel = $model->toSearchableArray();

            // make sure there is and id in the array - otherwise we will create duplicates all the time
            if (array_key_exists('id', $searchableModel)) {
                $searchableModel['id'] = $model->getScoutKey();
            }

            $document = $updateQuery->createDocument($searchableModel);

            $updateQuery->addDocument($document);
        });

        $updateQuery->addCommit();

        $this->client->update($updateQuery);
    }

    /**
     * Remove the given model from the index.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $models
     * @return void
     */
    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        $updateQuery = $this->client->createUpdate();

        $ids = $models->map(function ($model) {
            return $model->getScoutKey();
        });

        $updateQuery->addDeleteByIds($ids->toArray());
        $updateQuery->addCommit();

        $this->client->update($updateQuery);
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @return mixed
     */
    public function search(Builder $builder)
    {
        return $this->performSearch($builder);
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
        $offset = ($page-1) * $perPage;

        return $this->performSearch($builder, $perPage, $offset);
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  \Solarium\QueryType\Select\Result\Result $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results)
    {
        $ids = array_map(function ($document) {
            return $document->id;
        }, $results->getDocuments());

        return collect($ids);
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param  \Laravel\Scout\Builder $builder
     * @param  \Solarium\QueryType\Select\Result\Result $results
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        if (count($results->getDocuments()) === 0) {
            return Collection::make();
        }

        $models = $model->getScoutModelsByIds(
            $builder, collect($results->getDocuments())->pluck('id')->values()->all()
        )->keyBy(function ($model) {
            return $model->getScoutKey();
        });

        return Collection::make($results->getDocuments())->map(function ($document) use ($models) {
            if (isset($models[$document['id']])) {
                return $models[$document['id']];
            }
        })->filter()->values();
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  \Solarium\QueryType\Select\Result\Result $results
     * @return int
     */
    public function getTotalCount($results)
    {
        return $results->getNumFound();
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @param  int|null $perPage
     * @param  int|null $offset
     * @return mixed
     */
    protected function performSearch(Builder $builder, $perPage = null, $offset = null)
    {
        $selectQuery = $this->client->createSelect();

        $conditions = (empty($builder->query)) ? [] : [$builder->query];
        $conditions = array_merge($conditions, $this->filters($builder));

        $selectQuery->setQuery(implode(' ', $conditions));

        if(!is_null($perPage)) {
            $selectQuery->setStart($offset)->setRows($perPage);
        }

        // @todo callback return

        return $this->client->select($selectQuery);
    }

    /**
     * Get the filter array for the query.
     *
     * @param  \Laravel\Scout\Builder  $builder
     * @return array
     */
    protected function filters(Builder $builder)
    {
        return collect($builder->wheres)->map(function ($value, $key) {
            return sprintf('%s:"%s"', $key, $value);
        })->values()->all();
    }
}