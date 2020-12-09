<?php

class NeweggProduct implements Parser {

  private $url;

  public function __construct(string $url) {
    $this->url = $url;
  }

  public function parse(string $buffer) {
    $dom = new DomDocument();
    $dom->loadHTML($buffer);
    $xpath = new DomXPath($dom);
    $item = $xpath->query("//div[contains(@class, 'product-main')]")->item(0);
    $title = $xpath->query(".//h1[@class='product-title']", $item)->item(0);
    $buy = $xpath->query("//div[@id='ProductBuy']")->item(0);
    $button = $xpath->query(".//*[contains(@class, 'btn')]", $buy)->item(0);
    $product = trim($title->textContent);
    $status = trim($button->textContent);
    $icon = Status::IN_STOCK_ICON;
    $class = Status::IN_STOCK_CLASS;

    if (strtolower($status) === strtolower(NeweggStatus::SOLD_OUT)) {
      $icon = Status::SOLD_OUT_ICON;
      $class = Status::SOLD_OUT_CLASS;
    }
    elseif (strtolower($status) === strtolower(NeweggStatus::AUTO_NOTIFY)) {
      $icon = Status::AUTO_NOTIFY_ICON;
      $class = Status::AUTO_NOTIFY_CLASS;
    }

    $id = hash('md5', self::class . $this->url);
    $result = new Result($id, $product, $status, $icon, $class, $this->url);

    return [$result];
  }

}
