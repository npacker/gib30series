<?php

ob_start();

require_once 'includes/bootstrap.php';

bootstrap();

$route = strtok($_SERVER['REQUEST_URI'], '?');

switch ($route) {

  case '/':
    require 'routes' . DS . 'main.php';
    break;

  case '/ajax':
    require 'routes' . DS . 'ajax.php';
    break;

  default:
    break;

}

ob_end_flush();
exit();
