<?php

class NeweggItems implements Parser {

  private $url;

  public function __construct(string $url) {
    $this->url = $url;
  }

  public function parse(string $buffer) {
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

      if (strtolower($status) === strtolower(NeweggStatus::SOLD_OUT)) {
        $status = Status::SOLD_OUT;
        $icon = Status::SOLD_OUT_ICON;
        $class = Status::SOLD_OUT_CLASS;
      }
      elseif (strtolower($status) === strtolower(NeweggStatus::AUTO_NOTIFY)) {
        $status = Status::AUTO_NOTIFY;
        $icon = Status::AUTO_NOTIFY_ICON;
        $class = Status::AUTO_NOTIFY_CLASS;
      }
      else {
        $status = Status::IN_STOCK;
        $icon = Status::IN_STOCK_ICON;
        $class = Status::IN_STOCK_CLASS;
      }

      $id = new Id(slef::class . $url);
      $results[] = new Result($id, $product, $status, $icon, $class, $url);
    }

    return $results;
  }

}
