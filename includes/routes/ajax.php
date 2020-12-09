<?php

require 'fetch.php';

$results = fetch($settings['url'], $settings['cainfo']);
$json = json_encode($results);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

echo $json;
