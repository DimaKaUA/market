<?php 
if (version_compare(phpversion(), '5.1.0', '<') == true) { die ('PHP5.1 Only'); }

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/bootstrap.php';