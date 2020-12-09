<?php

class InStockStatus implements Status {

  public function label(): string {
    return 'In Stock';
  }

  public function icon(): string {
    return 'check_circle';
  }

  public function class(): string {
    return 'in-stock';
  }

}
