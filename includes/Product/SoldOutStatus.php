<?php

class SoldOutStatus implements Status {

  public function label(): string {
    return 'Sold Out';
  }

  public function icon(): string {
    return 'cancel';
  }

  public function class(): string {
    return 'sold-out';
  }

}
