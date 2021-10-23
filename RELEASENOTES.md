# Release Notes

## Version 3.0 (23 October 2021)

- Installable with 7.4+ (including PHP8)
- Typing added throughout
- PSR4 namespacing. Now in `Addwiki\Mediawiki\Api`

## Version 2.8 (16 February 2021)

- Installable with 7.3+ (including PHP8)
- Set `maxlag` parameter when `EditInfo` with maxlag is passed to `Addwiki\Addwiki\Mediawiki\Api\Service\RevisionSaver::save`

## Version 2.7 (15 February 2021)

- No notable changes

## Version 2.6 (2 February 2021)

- Initial release from new development monorepo
- All addwiki libraries now have aligned version numbers
- Require PHP 7.2+
- Fix PageGetter::getRevisionsFromResult undefined $revision['comment'] after an oversight/suppression of the edit summary. ([commit](https://github.com/addwiki/mediawiki-api/commit/5281c8f6c73d8e522a630e9c51cb4052d84eac67))
- Make `PagePurger::purge` purge `Page` objects that only have a title and no pageid ([commit](https://github.com/addwiki/mediawiki-api/commit/487c8e162cde8eeee66185a00fc86b5a4526fd27))

## Version 0.7.3 (14th January 2020)

* Installable with addwiki/mediawiki-datamodel:0.8

### Version 0.7.2 (20th November 2017)

* New parent class for all API service classes,
  with protected access on `Service::$api` to make it easier to subclass any services.
* File uploading improved, with the option of [chunked uploading](https://www.mediawiki.org/wiki/API:Upload#Chunked_uploading).
* Various fixes and improvements to the testing set-up and coding standards.

### Version 0.7.1 (8th March 2017)

* Fixed dependency on addwiki/mediawiki-datamodel

### Version 0.7 (March 2017)

* Documentation! This package now has a
  [dedicated documentation website](https://addwiki.readthedocs.io/projects/mediawiki-api/).
* A new NamespaceGetter service with which you can get all namespaces,
  or a single namespace by localised name, alias, or canonical name
  ([#39](https://github.com/addwiki/mediawiki-api/pull/39), [#41](https://github.com/addwiki/mediawiki-api/pull/41)).
* A new CategoryTraverser service for descending (all levels of) category trees
  and either retrieving all pages or performing some action on each page.
* A new method to PagePurger for purging multiple pages at once ([#36](https://github.com/addwiki/mediawiki-api/pull/36)). 
* All methods of the PageListGetter now continue their queries where the first request doesn't retrieve the whole result set
  ([#31](https://github.com/addwiki/mediawiki-api/pull/31)).
* Bug [#40](https://github.com/addwiki/mediawiki-api/pull/40) fixed with `RevisionSaver::save()` overwriting EditInfo if null.
* Integration tests: more documentation about how to run integration tests locally,
  and the tests are running on Travis CI.
* Lots of fixes to coding-standards and in-code documentation.

### Version 0.6 (3 August 2016)

* Adds newParser method to factory
* Use the new API continuation mode
* Fix ignored bot assertion in EditInfo

### Version 0.5.1 (7 September 2015)

* Adds ApiGenerator interface
* Adds AnonymousGenerator implementation of Generator
* Adds FluentGenerator implementation of Generator

### Version 0.5 (4 September 2015)

#### Breaks

* LogListGetter now requires mediawiki verison 1.25 or above
* PageListGetter now requires mediawiki verison 1.25 or above
* Removed ALL Options objects

#### Additions

* Introduces RevisionUndoer service
* Introduces UserCreator service
* Introduces FileUploader service
* Introduces ImageRotator service

#### Libs

* Using mediawiki-api-base 1.0
* Using mediawiki-datamodel 0.6

### Version 0.4 (13 January 2015)

* Issue#8 PageListGetter methods now construct pages with a Title object rather than string
* Page(Deleter|ListGetter|Mover|Protector|Restorer), User(Blocker|RightsChanger) service methods now require an *Options object rather than a selection of parameters.
* Implemented PageListGetter::getRandom

### Version 0.3 (2014-06-24)

* Removes NewEditInfo and NewRevision
* Moved basic api functionality to a separate base lib (mediawiki-api-base)
* Repos renamed to Getters
* PageGetter, RevisionSaver and UserGetter moved to the Service Namespace
* Introduces MediawikiFactory
* Introduces PageDeleter service
* Introduces PageListGetter service
* Introduces PageProtector service
* Introduces PagePurger service
* Introduces RevisionDeleter service
* Introduces RevisionPatroller service
* Introduces RevisionRollbacker service
* Introduces UserBlocker service
* Introduces UserRightsChanger service
* Introduces PageRestorer service
* Introduces RevisionRestorer service
* Correctly handle non existent users in UserGetter


### Version 0.2 (2014-02-23)

* Altered everything for changed in mediawiki-datamodel
* Removed Edit << action class
* Introduces NewEditInfo and NewRevision


### Version 0.1 (2014-02-23)

Initial release with the following features:

* MediawikiApi
* ApiUser
* MediawikiSession
* UsageExceptions
* PageRepo
* UserRepo
* EditSaver
* Edit << action
