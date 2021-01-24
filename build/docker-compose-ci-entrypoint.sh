#!/bin/bash

set -x

# Wait for the DB to be ready?
/dc-scripts/wait-for-it.sh $MYSQL_SERVER:3306 -t 300
sleep 1
/dc-scripts/wait-for-it.sh $MYSQL_SERVER:3306 -t 300

# Install MediaWiki
php maintenance/install.php --server="http://localhost:8877" --scriptpath= --dbtype mysql --dbuser $MYSQL_USER --dbpass $MYSQL_PASSWORD --dbserver $MYSQL_SERVER --lang en --dbname $MYSQL_DATABASE --pass LongCIPass123 SiteName CIUser

# Settings to make testing easier
echo "\$wgGroupPermissions['*']['noratelimit'] = true;" >> LocalSettings.php
echo "\$wgEnableUploads = true;" >> LocalSettings.php

# Run apache
apache2-foreground