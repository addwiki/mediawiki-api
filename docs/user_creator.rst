User Creator
============

The User Creator allows you to add a new user to a wiki.

To use it, first get a new ``UserCreator`` object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $userCreator = $services->newUserCreator();


Creating a user
---------------

.. code-block:: php

   $isUserCreated = $userCreator->create( 'Username', 'Password', 'Email' );

``create`` accepts the username, password and optionally an email address of the user you want to create. It returns a ``boolean`` that indicates if the create operation was successful.
