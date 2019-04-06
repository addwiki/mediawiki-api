Revision Patroller
==================

The Revision Patroller allows you to patrol a revision.

To use it, first get a new ``RevisionPatroller`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $revisionPatroller = $services->newRevisionPatroller();


Patrolling a revision
---------------------

.. code-block:: php

   $revision = new \Mediawiki\DataModel\Revision( /* ... */ );
   $isRevisionPatrolled = $revisionPatroller->patrol( $revision );

``patrol`` accepts a ``Revision`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the patrol operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
