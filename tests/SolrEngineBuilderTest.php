<?php

declare(strict_types=1);

//use App\SolrEngineTestModel;
use App\User;
// use Illuminate\Database\Eloquent\Collection;

// use ScoutEngines\Solr\SolrEngine;
// use Solarium\Client;
// use Solarium\QueryType\Update\Query\Query;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;


class SolrEngineBuilderTest extends TestCase
{

    /**
     * THIS TEST MUST BE RUN IN A LARAVEL (6) INSTALLATION
     * 
     * THE \USER CLASS MUST HAVE use Searchable;
     * 
     * FIRST USE factory(\App\User::class)->create() TO CREATE SOME USERS AND THEN RUN FILTERS, ETC
     * 
     * @todo - create a seeder to fill the database and then perform tests that confirm proper working
     * @todo - namespace Solarium\Core\Client\Request::getUri() test
     */
    public function testCustomBuilder() {

        $u = new User();
        //dd(get_class_methods($u));
        $builder = $u->search('*')->filter('name', 'Dr. Donnell Weissnat');
        dd(get_class_methods($builder));
        dd($builder->get());
        dd(get_class($builder));
        dd(get_class($u->search('hi')));
        
    }




}
