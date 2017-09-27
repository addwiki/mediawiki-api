Contributing
============

We welcome all contributions, be they code, documentation, or even just ideas about how to make this package better!

The best way to get started is to browse the `#addwiki board on Phabricator`_
and either work on one of the tasks already there or create a new one with details of what you want to work on.

.. _Addwiki board on Phabricator: https://phabricator.wikimedia.org/tag/addwiki/

Get the code
------------

The code is `hosted on GitHub`_. Clone the repository with::

    $ git clone https://github.com/addwiki/mediawiki-api.git

.. _hosted on GitHub: https://github.com/addwiki/mediawiki-api

Run the tests
-------------

After cloning the repository and updating the dependencies with Composer,
you should be able to run all **unit** tests with::

    ./vendor/bin/phpunit ./tests/unit

To run the **integration** tests you need to set up a local MediaWiki installation
(including with a ``admin`` administrator user with password ``admin123``)
and tell ``phpunit`` where to find it.

1. Copy ``./phpunit.xml.dist`` to ``./phpunit.xml`` and add the following section::

    <php>
        <env name="MEDIAWIKI_API_URL" value="http://localhost/path/to/your/wiki/api.php" />
    </php>

2. Create and promote a new user::

    $ php mediawiki/maintenance/createAndPromote.php --sysop WikiSysop wiki123sysop

Now all integration tests can be run with::

    ./vendor/bin/phpunit ./tests/integration
