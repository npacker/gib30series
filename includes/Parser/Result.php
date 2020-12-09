<?php

class Result {

  public $id;

  public $product;

  public $status;

  public $icon;

  public $class;

  public $url;

  public function __construct(string $id, string $product, string $status, string $icon, string $class, string $url) {
    $this->id = $id;
    $this->product = $product;
    $this->status = $status;
    $this->icon = $icon;
    $this->class = $class;
    $this->url = $url;
  }

}
