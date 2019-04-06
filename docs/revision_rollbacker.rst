Revision Rollbacker
===================

The Revision Rollbacker allows you to revert the last series of edits made to a wiki page.

To use it, first get a new ``RevisionRollbacker`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $revisionRollbacker = $services->newRevisionRollbacker();


Rollback a revision
-------------------

.. code-block:: php

   $revision = new \Mediawiki\DataModel\Revision( /* ... */ );
   $title = new \Mediawiki\DataModel\Title( /* ... */ );
   $isRevisionRollbacked = $revisionRollbacker->rollback( $revision, $title );

``rollback`` accepts a ``Revision`` and a ``Title`` object; these classes are part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the rollback operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
