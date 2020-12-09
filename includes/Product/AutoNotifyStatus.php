<?php

class AutoNotifyStatus implements Status {

  public function label(): string {
    return 'Auto Notify';
  }

  public function icon(): string {
    return 'info';
  }

  public function class(): string {
    return 'notify';
  }

}
