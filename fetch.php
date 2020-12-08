<?php

define('IN_STOCK', 'Add to cart');
define('SOLD_OUT', 'Sold Out');
define('AUTO_NOTIFY', 'Auto Notify');

function fetch() {
  $handle = curl_init('https://www.newegg.com/p/pl?N=100007709%20601357250');

  curl_setopt($handle, CURLOPT_FRESH_CONNECT, TRUE);
  curl_setopt($handle, CURLOPT_FORBID_REUSE, TRUE);
  curl_setopt($handle, CURLOPT_HEADER, FALSE);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
  curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($handle, CURLOPT_CAINFO, '/etc/ssl/certs/USERTrust_RSA_Certification_Authority.pem');

  $buffer = curl_exec($handle);

  curl_close($handle);

  $dom = new DomDocument();

  $dom->loadHTML($buffer);

  $xpath = new DomXPath($dom);
  $items = $xpath->query("//div[@class='item-cell']");
  $results = [];

  foreach ($items as $item) {
    $title = $xpath->query(".//a[@class='item-title']", $item)->item(0);
    $operate = $xpath->query(".//div[@class='item-operate']", $item)->item(0);
    $button = $xpath->query(".//*[contains(@class, 'btn')]", $operate)->item(0);

    $product = trim($title->textContent);
    $status = trim($button->textContent);
    $url = trim($title->getAttribute('href'));
    $icon = 'check_circle';
    $class = 'in-stock';

    if (strtolower($status) === strtolower(SOLD_OUT)) {
      $icon = 'cancel';
      $class = 'sold-out';
    }
    elseif (strtolower($status) === strtolower(AUTO_NOTIFY)) {
      $icon = 'info';
      $class = 'notify';
    }

    $results[] = [
      'product' => $product,
      'status' => $status,
      'icon' => $icon,
      'class' => $class,
      'url' => $url,
    ];
  }

  return $results;
}
