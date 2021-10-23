# mediawiki-api

[![GitHub issue custom search in repo](https://img.shields.io/github/issues-search/addwiki/addwiki?label=issues&query=is%3Aissue%20is%3Aopen%20%5Bmediawiki-api%5D)](https://github.com/addwiki/addwiki/issues?q=is%3Aissue+is%3Aopen+%5Bmediawiki-api%5D+)
[![Latest Stable Version](https://poser.pugx.org/addwiki/mediawiki-api/version.png)](https://packagist.org/packages/addwiki/mediawiki-api)
[![Download count](https://poser.pugx.org/addwiki/mediawiki-api/d/total.png)](https://packagist.org/packages/addwiki/mediawiki-api)

## Installation

Use composer to install the library and all its dependencies:

    composer require "addwiki/mediawiki-api:~3.0"

## Example Usage

```php
// Load all the stuff
require_once( __DIR__ . '/vendor/autoload.php' );

// Create an authenticated API and services
$auth = new \Addwiki\Mediawiki\Api\Client\Auth\UserAndPassword( 'username', 'password' )
$api = new \Addwiki\Mediawiki\Api\Client\Action\MediawikiApi( 'http://localhost/w/api.php', $auth );
$services = new \Addwiki\Mediawiki\Api\MediawikiFactory( $api );

// Get a page
$page = $services->newPageGetter()->getFromTitle( 'Foo' );

// Edit a page
$content = new \Addwiki\Mediawiki\DataModel\Content( 'New Text' );
$revision = new \Addwiki\Mediawiki\DataModel\Revision( $content, $page->getPageIdentifier() );
$services->newRevisionSaver()->save( $revision );

// Move a page
$services->newPageMover()->move(
	$services->newPageGetter()->getFromTitle( 'FooBar' ),
	new Title( 'FooBar' )
);

// Delete a page
$services->newPageDeleter()->delete(
	$services->newPageGetter()->getFromTitle( 'DeleteMe!' ),
	array( 'reason' => 'Reason for Deletion' )
);

// Create a new page
$newContent = new \Addwiki\Mediawiki\DataModel\Content( 'Hello World' );
$title = new \Addwiki\Mediawiki\DataModel\Title( 'New Page' );
$identifier = new \Addwiki\Mediawiki\DataModel\PageIdentifier( $title );
$revision = new \Addwiki\Mediawiki\DataModel\Revision( $newContent, $identifier );
$services->newRevisionSaver()->save( $revision );

// List all pages in a category
$pages = $services->newPageListGetter()->getPageListFromCategoryName( 'Category:Cat name' );
```

## Running the integration tests

To run the integration tests, you need to have a running MediaWiki instance. The tests will create pages and categories without using a user account so it's best if you use a test instance. Furthermore you need to turn off rate limiting by adding the line

   $wgGroupPermissions['*']['noratelimit'] = true;

to the `LocalSettings.php` of your MediaWiki.

By default, the tests will use the URL `http://localhost/w/api.php` as the API endpoint. If you have a different URL (e.g. `http://localhost:8080/w/api.php`), you need to configure the URL as an environment variable before running the tests. Example:

    export MEDIAWIKI_API_URL='http://localhost:8080/w/api.php'

**Warning:** Running the integration tests can take a long time to complete.
