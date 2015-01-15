These are the release notes for the [mediawiki-api](README.md).

## Version 0.5 (development)

* Introduces RevisionUndoer service
* Introduces UserCreator service
* Introduces FileUploader service
* Using mediawiki-api-base 0.6
* Using mediawiki-datamodel 0.3

## Version 0.4 (13 January 2015)

* Issue#8 PageListGetter methods now construct pages with a Title object rather than string
* Page(Deleter|ListGetter|Mover|Protector|Restorer), User(Blocker|RightsChanger) service methods now require an *Options object rather than a selection of parameters.
* Implemented PageListGetter::getRandom

## Version 0.3 (2014-06-24)

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


## Version 0.2 (2014-02-23)

* Altered everything for changed in mediawiki-datamodel
* Removed Edit << action class
* Introduces NewEditInfo and NewRevision


## Version 0.1 (2014-02-23)

Initial release with the following features:

* MediawikiApi
* ApiUser
* MediawikiSession
* UsageExceptions
* PageRepo
* UserRepo
* EditSaver
* Edit << action
