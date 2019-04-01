Log List Getter
===============

The Log List Getter allows you to get a list of logged events in a wiki.

To use it, first get a new LogListGetter object from the factory:

.. code-block:: php

   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $logListGetter = $services->newLogListGetter();


Getting all logged events
-------------------------
.. code-block:: php

   $logList = $logListGetter->getLogList();

``getLogList`` returns a ``LogList`` object; this class is part of the `addwiki/mediawiki-datamodel`_ package.

.. _addwiki/mediawiki-datamodel: https://packagist.org/packages/addwiki/mediawiki-datamodel

 
Getting events logged by a certain user
---------------------------------------
``getLogList`` also accepts the same arguments as the API `list=logevents`_ query. For example to get log events related to a certain title and made by a certain user:

.. _list=logevents: https://www.mediawiki.org/wiki/API:Logevents

* ``letitle`` List log entries related to this title.
* ``leuser`` List log entries made by this user.

.. code-block:: php

   $logList = $logListGetter->getLogList([
       'letitle' => 'Page title',
       'leuser' => 'Username'
   ]);