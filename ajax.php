<?php

ini_set('display_errors', 0);
ini_set('error_reporting', E_ALL);

require 'fetch.php';

$results = fetch();
$json = json_encode($results);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

echo $json;

exit();
