#!/bin/bash
##
## This script installs MediaWiki to ./build/mediawiki (relative to the directory from which it's called).
##

## Check inputs.
if [ -z $MEDIAWIKI_VERSION ]; then
    echo "You must specify the MEDIAWIKI_VERSION environment variable"
    exit 0
fi

## Set some paths.
BUILDDIR=$(cd $(dirname "$0"); pwd -P)"/../build"
if [ ! -d $BUILDDIR ]; then
    mkdir "$BUILDDIR"
fi
INSTALLDIR="$BUILDDIR/mediawiki"
if [ -d "$INSTALLDIR" ]; then
    rm -r "$INSTALLDIR"
fi
echo "Installing MediaWiki $MEDIAWIKI_VERSION to $INSTALLDIR"

## Get the required version, and unpack it to `./build/mediawiki`.
if [ ! -s "$BUILDDIR/$MEDIAWIKI_VERSION.tar.gz" ]; then
    wget --directory-prefix="$BUILDDIR" "https://github.com/wikimedia/mediawiki/archive/$MEDIAWIKI_VERSION.tar.gz"
fi
cd "$BUILDDIR"
echo "Unpacking"
tar -zxf "$MEDIAWIKI_VERSION.tar.gz"
mv "mediawiki-$MEDIAWIKI_VERSION" $INSTALLDIR

## Install MediaWiki.
cd "$INSTALLDIR"
WIKIDB="test_wiki1"
echo "Creating database as MySQL root user"
PASSARG=""
if [ -n "$DBPASS" ]; then
    PASSARG="-p$DBPASS"
fi
mysql "$PASSARG" -uroot -e "DROP DATABASE IF EXISTS $WIKIDB"
mysql "$PASSARG" -uroot -e "CREATE DATABASE $WIKIDB"
echo "Updating dependencies (Composer)"
composer install
echo "Installing TestWiki1 wiki"
php maintenance/install.php --dbtype mysql --dbuser "root" --dbpass "$DBPASS" --dbname $WIKIDB --scriptpath "" --pass admin123admin TestWiki1 admin

# Add some extra configuration to LocalSettings.php
cat << 'EOF' >> "$INSTALLDIR/LocalSettings.php"
$wgEnableUploads = true;
$wgShowExceptionDetails = true;
$wgCacheDirectory = __DIR__."/images/tmp";
$wgServer = "http://127.0.0.1:8081";
$wgUsePathInfo = false;
$wgJobRunRate = 200;
EOF
