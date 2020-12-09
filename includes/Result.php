<?php

class Result {

  public $product;

  public $status;

  public $icon;

  public $class;

  public $url;

  public function __construct(string $product, string $status, string $icon, string $class, string $url) {
    $this->product = $product;
    $this->status = $status;
    $this->icon = $icon;
    $this->class = $class;
    $this->url = $url;
  }

}
