<?php

declare(strict_types=1);

namespace ScoutEngines\Solr;

use Illuminate\Database\Eloquent\Collection;
//use Laravel\Scout\Engines\Engine;
use Solarium\Client;
use ScoutEngines\Solr\Builder;
use ScoutEngines\Solr\Engine;


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

        $query = $this->client->createUpdate();

        $models->each(function ($model) use (&$query) {
            $attrs = array_filter($model->toSearchableArray(), function ($value) {
                return !\is_null($value);
            });

            // Make sure there is an ID in the array,
            // otherwise we will create duplicates all the time.
            if (!\array_key_exists('id', $attrs)) {
                $attrs['id'] = $model->getScoutKey();
            }

            // Add model class to attributes for flushing.
            $attrs['_class'] = \get_class($model);

            $document = $query->createDocument($attrs);
            $query->addDocument($document);
        });

        $query->addCommit();

        $endpoint = $models->first()->searchableAs();
        //dd($this->client->getEndpoints());
        //dd($endpoint);
        $this->client->update($query, 'default');
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

        $ids = $models->map(function ($model) {
            return $model->getScoutKey();
        });

        $query = $this->client->createUpdate();
        $query->addDeleteByIds($ids->toArray());
        $query->addCommit();

        $endpoint = $models->first()->searchableAs();
        $this->client->update($query, $endpoint);
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \ScoutEngines\Solr\Builder $builder
     * @return mixed
     */
    public function search( Builder $builder)
    {
        echo __FILE__ . ":" . __LINE__ . "\n"; 
        dd(get_class($builder));
        return $this->performSearch($builder);
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \ScoutEngines\Solr\Builder $builder
     * @param  int $perPage
     * @param  int $page
     * @return mixed
     */
    public function paginate(Builder $builder, $perPage, $page)
    {
        $offset = ($page - 1) * $perPage;

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
     * @param  \ScoutEngines\Solr\Builder $builder
     * @param  \Solarium\QueryType\Select\Result\Result $results
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function map(Builder $builder, $results, $model)
    {
        if (\count($results->getDocuments()) === 0) {
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
     * Flush all of the model's records from the engine.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function flush($model)
    {
        $class = \is_object($model) ? \get_class($model) : false;
        if ($class) {
            $query = $this->client->createUpdate();
            $query->addDeleteQuery("_class:{$class}");
            $query->addCommit();
            $this->client->update($query);
        }
    }

    /**
     * Perform the given search on the engine.
     *
     * @param  \ScoutEngines\Solr\Builder  $builder
     * @param  int|null $perPage
     * @param  int|null $offset
     * @return mixed
     */
    protected function performSearch(Builder $builder, $perPage = null, $offset = null)
    {
        $selectQuery = $this->client->createSelect();

        $fq_array = [];
        foreach($builder->fqs as $fq) {
            foreach($fq as $field=>$values) {
                if ( is_array($values) ) {
                    $stringied = implode( ' OR ' . $field . ":", $values);
                    $par = $field.":".$stringied;
                } else {
                    $par = $field.":".$values;
                }
                $fq_array[] = $par;
            }
        }

        $selectQuery->addParam('fq', $fq_array);

        if(!empty($builder->facets)) {
            $selectQuery->addParam('facet', 'on');
            $selectQuery->addParam('facet.field', $builder->facets);
        }

        if(!empty($builder->order)) {
            $selectQuery->addParam('sort', $builder->order);
        }

        $conditions = (empty($builder->query)) ? [] : [$builder->query];
        $conditions = array_merge($conditions, $this->filters($builder));

        $selectQuery->setQuery(implode(' ', $conditions));

        if (!\is_null($perPage)) {
            $selectQuery->setStart($offset)->setRows($perPage);
        }

        // @todo callback return
        //dd($selectQuery);
        //dd($this->client);

        return $this->client->select($selectQuery);
    }

    /**
     * Get the filter array for the query.
     *
     * @param  \ScoutEngines\Solr\Builder  $builder
     * @return array
     */
    protected function filters(Builder $builder)
    {
        /*
        dd( collect($builder->wheres)->map(function ($value, $key) {
            return sprintf('%s:"%s"', $key, $value);
        })->values()->all() );
        dd($builder->wheres);
        */
        return collect($builder->wheres)->map(function ($value, $key) {
            return sprintf('%s:"%s"', $key, $value);
        })->values()->all();
    }
}
