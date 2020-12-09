<?php

class NeweggProduct {

  private $url;

  private $request;

  public function __construct(string $url, CurlRequest $request) {
    $this->url = $url;
    $this->request = $request;
  }

  public function fetch(): array {
    $buffer = $this->request->send($this->url);

    return $this->parse($buffer);
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

    $result = [
      'product' => $product,
      'status' => $status,
      'icon' => $icon,
      'class' => $class,
      'url' => $this->url,
    ];

    return [$result];
  }

}
