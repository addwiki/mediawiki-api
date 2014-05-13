These are the release notes for the [mediawiki-api](README.md).

## Version 0.3 (under development)

* Removes NewEditInfo and NewRevision
* Introduces MediawikiFactory
* PageRepo, RevisionSaver and UserRepo moved to the Service Namespace
* Moved basic api functionality to a separate base lib (mediawiki-api-base)
* Introduces PageDeleter service
* Introduces PageListRepo service
* Introduces PageProtector service
* Introduces PagePurger service
* Introduces RevisionDeleter service
* Introduces RevisionPatroller service
* Introduces RevisionRollbacker service
* Introduces UserBlocker service
* Introduces UserRightsChanger service
* Introduces PageRestorer service
* Introduces RevisionRestorer service
* Correctly handle non existent users in UserRepo


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
