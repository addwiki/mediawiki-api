User Getter
===========

The User Getter allows you to get information about a user in a wiki.

To use it, first get a new UserGetter object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $userGetter = $services->newUserGetter();


Getting user information
------------------------

.. code-block:: php

   $userInfo = $userGetter->getFromUsername( 'Username' );

``getFromUsername`` accepts the username of the user whose information you want to get. It returns a ``User`` object;
this class is part of the `addwiki/mediawiki-datamodel`_ package.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel
