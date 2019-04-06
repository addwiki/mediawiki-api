User Blocker
============

The User Blocker allows you to block a user in a wiki.

To use it, first get a new ``UserBlocker`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $userBlocker = $services->newUserBlocker();


Blocking a user
---------------

.. code-block:: php

   $user = new \Mediawiki\DataModel\User( /* ... */ );
   $isBlocked = $userBlocker->block( $user );

``block`` accepts a ``User`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the block operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel


Blocking a user for a certain period
------------------------------------

``block`` also accepts the same arguments as API `action=block`_. For example to block a user until a certain time:

.. _action=block: https://www.mediawiki.org/wiki/API:Block

* ``expiry`` The time the block will expire.
* ``reason`` Reason for the block.

.. code-block:: php

   $user = new \Mediawiki\DataModel\User( /* ... */ );
   $extraParams = [
      'expiry' => '2019-02-25T07:27:50Z',
      'reason' => 'Your reason'
   ];
   $isBlocked = $userBlocker->block( $user, $extraParams );