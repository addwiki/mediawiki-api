Revision Saver
==============

The Revision Saver allows you to save an edit to a revision.

To use it, first get a new ``RevisionSaver`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $revisionSaver = $services->newRevisionSaver();


Saving an edit
--------------

.. code-block:: php

   $revision = new \Mediawiki\DataModel\Revision( /* ... */ );
   $editInfo = new \Mediawiki\DataModel\EditInfo( /* ... */ );
   $isRevisionSaved = $revisionSaver->save( $revision, $editInfo );

``save`` accepts a ``Revision`` and an ``EditInfo`` object; these classes are part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the save operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
