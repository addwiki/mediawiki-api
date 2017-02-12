#! /bin/bash

set -x

originalDirectory=$(pwd)

MW='1.28.0'
DBTYPE='mysql'
PHPVERSION=`phpenv version-name`

if [ "${PHPVERSION}" = 'hhvm' ]
then
	PHPINI=/etc/hhvm/php.ini
	echo "hhvm.enable_zend_compat = true" >> $PHPINI
fi

mkdir ./../web
cd ./../web

wget https://github.com/wikimedia/mediawiki/archive/$MW.tar.gz
tar -zxf $MW.tar.gz
mv mediawiki-$MW w
cd w

composer self-update
composer install

# Try composer install again... this tends to fail from time to time
if [ $? -gt 0 ]; then
	composer install
fi

mysql -e 'CREATE DATABASE mediawiki;'
php maintenance/install.php --dbtype $DBTYPE --dbuser root --dbname mediawiki --dbpath $(pwd) --pass adminpass TravisWiki admin

cd $originalDirectory
