Revision Deleter
================

The Revision Deleter allows you to delete the content of a revision.

To use it, first get a new ``RevisionDeleter`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $revisionDeleter = $services->newRevisionDeleter();


Deleting a revision
-------------------

.. code-block:: php

   $revision = new \Mediawiki\DataModel\Revision( /* ... */ );
   $isRevisionDeleted = $revisionDeleter->delete( $revision );

``delete`` accepts a ``Revision`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the delete operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
