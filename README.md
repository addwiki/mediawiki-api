mediawiki-api
==================
[![Build Status](https://travis-ci.org/addwiki/mediawiki-api.png?branch=master)](https://travis-ci.org/addwiki/mediawiki-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/badges/coverage.png?s=5bce1c1f0939d278ac715c7846b679a61401b1de)](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/badges/quality-score.png?s=4182ebaf18fb0b22af9bc3e7941fd4e3524c932e)](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/addwiki/mediawiki-api/version.png)](https://packagist.org/packages/addwiki/mediawiki-api)
[![Download count](https://poser.pugx.org/addwiki/mediawiki-api/d/total.png)](https://packagist.org/packages/addwiki/mediawiki-api)

## Installation

Use composer to install the library and all its dependencies:

    composer require "addwiki/mediawiki-api:dev-master"

## Example Usage

```php
require_once( __DIR__ . '/vendor/autoload.php' );
$services = new \Mediawiki\Api\ServiceFactory(
	new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' )
);

$revision = $services->newPageRepo()->getFromTitle( 'Foo' )->getRevisions()->getLatest();
$revision->getContent()->setText( 'NewText' );
$services->newRevisionSaver()->save( $revision );
```
