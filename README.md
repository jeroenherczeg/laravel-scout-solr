# Solr Driver for Laravel Scout

<p align="center"><img src="http://lucene.apache.org/solr/assets/identity/Solr_Logo_on_white.png" width="200px"><br><br></p>

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Laravel Scout][ico-laravel-scout]][link-laravel-scout]
[![Apache Solr][ico-solr]][link-solr]
[![PHP][ico-php]][link-php]

## Problem?

If you have **any** problems, questions or comments, feel free to submit an [issue](link-issue) and I will reply to you as soon as possible.

## Prerequisites

Install [Laravel Scout](https://laravel.com/docs/5.7/scout#installation).

## Install

Install via Composer

``` bash
$ composer require jeroenherczeg/laravel-scout-solr
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

### Solr setup

```
docker pull solr

docker run --name laravel_scout -d -p 8983:8983 -t solr

docker exec -it --user=solr laravel_scout bin/solr create_core -c scout

```

Go to http://localhost:8983/solr/#/scout


## Usage

Now you can use Laravel Scout as described in the [official documentation](https://laravel.com/docs/5.7/scout)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jeroen@herczeg.be instead of using the issue tracker.

## Credits

- [Jeroen Herczeg][link-author]
- [solariumphp/solarium](https://github.com/solariumphp/solarium)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jeroenherczeg/laravel-scout-solr.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jeroenherczeg/laravel-scout-solr/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jeroenherczeg/laravel-scout-solr.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jeroenherczeg/laravel-scout-solr.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jeroenherczeg/laravel-scout-solr.svg?style=flat-square
[ico-laravel-scout]: https://img.shields.io/badge/laravel%20scout-v5-blue.svg?style=flat-square
[ico-solr]: https://img.shields.io/badge/apache%20solr-7.5-blue.svg?style=flat-square
[ico-php]: https://img.shields.io/badge/php-7-blue.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jeroenherczeg/laravel-scout-solr
[link-travis]: https://travis-ci.org/jeroenherczeg/laravel-scout-solr
[link-scrutinizer]: https://scrutinizer-ci.com/g/jeroenherczeg/laravel-scout-solr/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jeroenherczeg/laravel-scout-solr
[link-downloads]: https://packagist.org/packages/jeroenherczeg/laravel-scout-solr
[link-author]: https://github.com/jeroenherczeg
[link-contributors]: ../../contributors
[link-laravel-scout]: https://laravel.com/docs/5.7/scout
[link-solr]: http://lucene.apache.org/solr/
[link-php]: http://php.net/
[link-issues]: https://github.com/jeroenherczeg/laravel-scout-solr/issues
