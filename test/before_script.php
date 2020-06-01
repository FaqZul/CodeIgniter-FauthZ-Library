<?php
$path = getenv('TRAVIS_BUILD_DIR') . '/';
$pathCI = $path . '../CodeIgniter/';
$arr = [
	'name' => 'faqzul/codeigniter-fauthz-library-test',
	'require' => ['faqzul/codeigniter-fauthz-library' => 'dev-' . getenv('TRAVIS_COMMIT')],
	'require-dev' => ['phpunit/phpunit' => '^4.0 || ^5.0'],
	'repositories' => [['type' => 'path', 'url' => $path, 'options' => ['symlink' => FALSE]]]];
$json = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$json = str_replace('    ', "\t", $json);
file_put_contents($pathCI . 'composer.json', $json);

$cdb = file_get_contents($pathCI . 'application/config/database.php');
$cdb = str_replace("'hostname' => 'localhost'", "'hostname' => '" . getenv('TRAVIS_DBHOST') . "'", $cdb);
$cdb = str_replace("'username' => ''", "'username' => '" . getenv('TRAVIS_DBUSER') . "'", $cdb);
if (($dbPass = getenv('TRAVIS_DBPASS')) !== FALSE)
	$cdb = str_replace("'password' => ''", "'password' => '$dbPass'", $cdb);
$cdb = str_replace("'database' => ''", "'database' => 'fauthz'", $cdb);
$cdb = str_replace("'save_queries' => TRUE", (($dbPort = getenv('TRAVIS_DBPORT')) !== FALSE) ? "'save_queries' => FALSE,\n\t'port' => $dbPort": "'save_queries' => FALSE", $cdb);
file_put_contents($pathCI . 'application/config/database.php', $cdb);

$cwe = file_get_contents($pathCI . 'application/controllers/Welcome.php');
$cwe = str_replace('$this->load->view(\'welcome_message\');', '', $cwe);
file_put_contents($pathCI . 'application/controllers/Welcome.php', $cwe);
