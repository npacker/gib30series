<?php

/**
 * Shorthand for the platform-specific directory separator.
 * @var string
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Shorthand for the platform-specific path separator.
 * @var string
 */
define('PS', PATH_SEPARATOR);

/**
 * Shorthand for the current directory of "bootstrap.php" (this file).
 * @var string
 */
define('ROOT', __DIR__);

/**
 * Clear all open output buffers. This is used for handling fatal errors or
 * uncaught exceptions so that partial or incomplete output is not displayed to
 * the user.
 */
function clean_all_buffers() {
  while (ob_get_level() != 0) {
    ob_end_clean();
  }
}

/**
 * Recursively glob all directories under the given path, returning the
 * full directory tree as a one-dimensional array.
 *
 * @return array
 */
function glob_dir_recursive($path) {
  $include_paths = glob($path . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT);

  foreach ($include_paths as $include_path) {
    $include_paths = array_merge($include_paths, glob_dir_recursive($include_path));
  }

  return $include_paths;
}

/**
 * Autoload any class in the "/includes" directory.
 *
 * The class name and file name must be identical (case sensitive).
 */
function class_autoloader($class) {
  static $classes = array();
  static $include_paths = array();

  if (isset($classes[$class])) {
    return;
  }

  if (empty($include_paths)) {
    $include_paths = array_merge(array(ROOT), glob_dir_recursive(ROOT));
  }

  foreach ($include_paths as $include_path) {
    $file_path = $include_path . DS . $class . '.php';

    if (file_exists($file_path)) {
      $classes[$class] = $file_path;
      require_once $file_path;
      break;
    }
  }
}

/**
 * Handle fatal errors.
 */
function fatal_error_handler() {
  $error = error_get_last();

  switch ($error['type']) {
    case E_COMPILE_ERROR:
    case E_CORE_ERROR:
    case E_ERROR:
    case E_PARSE:
    case E_USER_ERROR:
      clean_all_buffers();
      printf('<strong>Fatal error</strong>: %s in %s on line %d', $error['message'], $error['file'], $error['line']);
      exit();
  }
}

/**
 * Throw all errors as exceptions.
 */
function error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException(sprintf('%s in %s on line %d', $errstr, $errfile, $errline), 0, $errno, $errfile, $errline);
}

/**
 * Handle uncaught exceptions.
 */
function exception_handler(Throwable $e) {
  clean_all_buffers();
  printf('<strong>Uncaught exception:</strong> %s on line %d of %s', $e->getMessage(), $e->getLine(), $e->getFile());
  exit();
}

/**
 * Bootstrap the current request.
 *
 * Initalize settings and load required files and services.
 */
function bootstrap() {
  ini_set('display_errors', 0);
  ini_set('error_reporting', E_ALL);

  register_shutdown_function('fatal_error_handler');

  set_error_handler('error_handler');
  set_exception_handler('exception_handler');

  set_include_path(ROOT);

  spl_autoload_register('class_autoloader');
}
