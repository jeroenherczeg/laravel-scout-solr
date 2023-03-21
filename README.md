# Solr Driver for Laravel Scout

<p align="center"><img src="http://lucene.apache.org/solr/assets/identity/Solr_Logo_on_white.png" width="200px"><br><br></p>

## Documentation

Not ready yet.

## Problems, questions or comments?

If you have **any** problems, questions or comments, feel free to submit an [issue](link-issue) and I will reply to you as soon as possible.


## Prerequisites

Install [Laravel Scout](https://laravel.com/docs/10.x/scout#main-content.

## Install

Install via Composer

``` bash
$ composer require semihyilmaz/laravel-scout-solr
```

Set your SCOUT_DRIVER to solr:

```
// .env

...

SCOUT_DRIVER=solr
```


You must add the Scout service provider and the Solr engine service provider in your app.php config:

```
// config/app.php

'providers' => [
    ...
        /*
         * Package Service Providers...
         */
        Laravel\Scout\ScoutServiceProvider::class,
        ScoutEngines\Solr\SolrProvider::class,
],
```

Add the Solr configuration to the scout config file:

```php
// config/scout.php

...

    /*
    |--------------------------------------------------------------------------
    | Solr Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Solr settings. Solr is the popular, blazing
    | -fast, open source enterprise search platform built on Apache Lucene.
    | If necessary, you can override the configuration in your .env file.
    |
    */

    'solr' => [
        'host' => env('SOLR_HOST', '127.0.0.1'),
        'port' => env('SOLR_PORT', '8983'),
        'path' => env('SOLR_PATH', '/solr/'),
        'core' => env('SOLR_CORE', 'scout'),
    ],
```

## Usage

Now you can use Laravel Scout as described in the [official documentation](https://laravel.com/docs/10.x/scout#main-content)

## Using Solr with Laravel Homestead

You can install Solr within your Homestead virtual machine.

See the [Solr Install On Centos-Rocky-Alma Linux](https://www.semihyilmaz.com/centos-7-uzerine-solr-kurulumu/) for more information.




## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jeroen@herczeg.be instead of using the issue tracker.

## Credits

- [Semih YILMAZ][link-author]
- [Jeroen Herczeg][link-author]
- [solariumphp/solarium](https://github.com/solariumphp/solarium)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-downloads]: https://packagist.org/packages/jeroenherczeg/laravel-scout-solr
[link-author]: https://github.com/semihyilmaz
[link-contributors]: ../../contributors
[link-laravel-scout]: https://laravel.com/docs/10.x/scout#main-content
[link-solr]: http://lucene.apache.org/solr/
[link-php]: http://php.net/
[link-issues]: https://github.com/jeroenherczeg/laravel-scout-solr/issues
