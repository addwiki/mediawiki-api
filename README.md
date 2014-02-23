mediawiki-api
==================
[![Build Status](https://travis-ci.org/addwiki/mediawiki-api.png?branch=master)](https://travis-ci.org/addwiki/mediawiki-api)
[![Code Coverage](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/badges/coverage.png?s=5bce1c1f0939d278ac715c7846b679a61401b1de)](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/badges/quality-score.png?s=4182ebaf18fb0b22af9bc3e7941fd4e3524c932e)](https://scrutinizer-ci.com/g/addwiki/mediawiki-api/)

## Installation

Use composer to install the library and all its dependencies:

    composer require "addwiki/mediawiki-api:0.1.*"

## Example Usage

```php
require_once ( __DIR__ . '/vendor/autoload.php' );

$api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
$repo = new Mediawiki\Api\Repos\PageRepo( $api );
$saver = new \Mediawiki\Api\Savers\RevisionSaver( $api );

$page = $repo->getFromTitle( 'Foo' );
$newRev = \Mediawiki\Api\DataModel\NewRevision::fromRevision( $page->getRevisions()->getLatest() );
$newRev->setContent( 'blublub' );
$saver->save( $newRev );
```