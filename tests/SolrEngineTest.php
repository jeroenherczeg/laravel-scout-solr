<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use ScoutEngines\Solr\SolrEngine;
use Solarium\Client;
use Solarium\QueryType\Update\Query\Query;

class SolrEngineTest extends TestCase
{
    public function testUpdateAddsModelsToTheIndex()
    {
        $solrClient = $this->getMockBuilder(Client::class)
            ->setMethods(['update'])
            ->getMock();

        $query = new Query();

        $solrClient->expects($this->once())
            ->method('update')
            ->with($this->equalTo($query));

        $engine = new SolrEngine($solrClient);

        $engine->update(Collection::make([new SolrEngineTestModel]));
    }

    public function testDeleteRemovesUpdatesFromTheIndex()
    {
    }

    public function testSearchSendsCorrectConditionsToSolr()
    {
    }

    public function testResultsGetMappedToTheCorrectModels()
    {
    }
}
