<?php

class Id {

  private $value;

  public function __construct(string $value) {
    $this->value = $value;
  }

  public function __toString() {
    return hash('sha1', $this->value);
  }

}
