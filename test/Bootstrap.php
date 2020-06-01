<?php
$_SERVER['argv'] = ['index.php', 'welcome'];
$_SERVER['CI_ENV'] = 'testing';
require_once getenv('TRAVIS_BUILD_DIR') . '/../CodeIgniter/index.php';
