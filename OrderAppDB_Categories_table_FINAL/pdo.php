<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'orderappdb');
define('DB_USER', 'root');
define('DB_PASS', '');

define('DB_CSET', 'utf8');

$DB_OPTS = array (
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false,
	PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CSET;

$PDO = new PDO($dsn, DB_USER, DB_PASS, $DB_OPTS);
?>
