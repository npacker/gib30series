<?php

class Product {

  private $product;

  private $url;

  private $status;

  public function __construct(string $product, string $url, Status $status) {
    $this->product = $product;
    $this->url = $url;
    $this->status = $status;
  }

  public function product(): string {
    return $this->product;
  }

  public function url(): string {
    return $this->url;
  }

  public function status(): Status {
    return $this->status;
  }

}
