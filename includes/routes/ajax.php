<?php

$options = new CurlOptions($settings['cainfo']);
$requests = [];

foreach ($settings['urls'] as $type => $url) {
  $parser = new $type($url);
  $requests[] = new CurlSubRequest($url, $options, $parser);
}

$multi = new CurlMultiRequest(...$requests);
$results = $multi->execute();
$json = json_encode($results);

header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

echo $json;
