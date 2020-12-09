<?php

class NeweggItems {

  private $url;

  private $request;

  public function __construct(string $url, CurlRequest $request) {
    $this->url = $url;
    $this->request = $request;
  }

  public function fetch(): array {
    $buffer = $this->request->send($this->url);
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

      if (strtolower($status) === strtolower(NeweggStatus::SOLD_OUT)) {
        $icon = 'cancel';
        $class = 'sold-out';
      }
      elseif (strtolower($status) === strtolower(NeweggStatus::AUTO_NOTIFY)) {
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

}
