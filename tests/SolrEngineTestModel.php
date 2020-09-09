<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class SolrEngineTestModel extends Model
{
    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id'   => 1,
            'name' => 'Laravel Scout Solr Engine',
        ];
    }
}
