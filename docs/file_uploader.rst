Uploading files
===============

Basic usage
-----------

To upload a single, small-sized file:

.. code-block:: php
   :linenos:

   // Construct the API.
   $api = new \Mediawiki\Api\MediawikiApi( 'http://localhost/w/api.php' );
   $services = new \Mediawiki\Api\MediawikiFactory( $api );
   $fileUploader = $services->newFileUploader();

   // Upload the file.
   $fileUploader->upload( 'The_file.png', '/full/path/to/the_file.png' );

If you need to work with larger files, you can switch to chunked uploading:

.. code-block:: php
   :linenos:

   // Upload the file in 10 MB chunks.
   $fileUploader = $services->newFileUploader();
   $fileUploader->setChunkSize( 1024 * 1024 * 10 );
   $fileUploader->upload( 'The_file.png', '/full/path/to/the_file.png' );
