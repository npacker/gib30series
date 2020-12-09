<?php

$request = new CurlRequest($settings['cainfo']);
$method = new NeweggItems($settings['url'], $request);
$results = $method->fetch();
$json = json_encode($results);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

echo $json;
