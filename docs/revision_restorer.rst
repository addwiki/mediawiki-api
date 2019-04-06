Revision Restorer
=================

The Revision Restorer allows you to restore content that has been revision-deleted.

To use it, first get a new ``RevisionRestorer`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $revisionRestorer = $services->newRevisionRestorer();


Restoring a revision
--------------------

.. code-block:: php

   $revision = new \Mediawiki\DataModel\Revision( /* ... */ );
   $isRevisionRestored = $revisionRestorer->restore( $revision );

``restore`` accepts a ``Revision`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the restore operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
