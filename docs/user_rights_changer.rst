User Rights Changer
===================

The User Rights Changer allows you to add rights to or remove rights from a user.

To use it, first get a new ``UserRightsChanger`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $userRightsChanger = $services->newUserRightsChanger();


Changing user rights
--------------------

.. code-block:: php

   $user = new \Mediawiki\DataModel\User( /* ... */ );
   $add = [ 'autopatrolled', 'uploader' ];
   $remove = [ 'bureaucrat', 'steward', 'accountcreator' ];
   $isRightsChanged = $userRightsChanger->change( $user, $add, $remove );

``change`` accepts a string array of rights to add or remove and a ``User`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package. It returns a ``boolean`` that indicates if the change operation was successful.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel


Changing user rights for a certain period
-----------------------------------------

``change`` also accepts the same arguments as API `action=userrights`_. For example to change user rights until a certain time:

.. _action=userrights: https://www.mediawiki.org/wiki/API:User_group_membership

* ``expiry`` The time the user-rights change will expire.
* ``reason`` Reason for changing the user's rights.

.. code-block:: php

   $user = new \Mediawiki\DataModel\User( /* ... */ );
   $add = [ 'autopatrolled', 'uploader' ];
   $remove = [ 'bureaucrat', 'steward', 'accountcreator' ];
   $extraParams = [
      'expiry' => '2019-02-25T07:27:50Z',
      'reason' => 'Your reason'
   ];
   $isRightsChanged = $userRightsChanger->change( $user, $add, $remove, $extraParams );
