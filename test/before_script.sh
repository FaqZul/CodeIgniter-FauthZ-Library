#!/usr/bin/env bash
if [ -z $TRAVIS_BUILD_DIR ]; then
	export TRAVIS_BUILD_DIR=/var/www/GIT/CodeIgniter-FauthZ-Library
elif [ -z $TRAVIS_DBHOST ]; then
	export TRAVIS_DBHOST=127.0.0.1
elif [ -z $TRAVIS_DBUSER ]; then
	export TRAVIS_DBUSER=root
elif [ -z $TRAVIS_DBPASS ]; then
	export TRAVIS_DBPASS=
fi
composer create-project --no-install --no-progress codeigniter/framework:~3.1.11 $TRAVIS_BUILD_DIR/../CodeIgniter
php $TRAVIS_BUILD_DIR/test/before_script.php
cd $TRAVIS_BUILD_DIR/../CodeIgniter && composer update
