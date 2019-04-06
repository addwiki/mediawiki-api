Revision Undoer
===============

The Revision Undoer allows you to undo a certain revision in a wiki.

To use it, first get a new ``RevisionUndoer`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $revisionUndoer = $services->newRevisionUndoer();


Undoing a revision
------------------

.. code-block:: php

   $revision = new \Mediawiki\DataModel\Revision( /* ... */ );
   $isRevisionUndone = $revisionUndoer->undo( $revision );

``undo`` accepts a ``Revision`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the undo operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
