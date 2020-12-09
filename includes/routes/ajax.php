<?php

$options = new CurlOptions($settings['cainfo']);
$request = new CurlRequest($options);
$results = [];

foreach ($settings['urls'] as $type => $url) {
  $method = new $type($url, $request);

  $results = array_merge($results, $method->fetch());
}

$json = json_encode($results);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

echo $json;
