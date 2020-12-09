<?php

class CurlMultiRequest {

  private $multi;

  private $requests;

  public function __construct(Request ...$requests) {
    $this->multi = curl_multi_init();
    $this->requests = $requetss;

    foreach ($this->requests as $request) {
      curl_multi_add_handle($this->multi, $request->handle());
    }
  }

  public function __destruct() {
    curl_multi_close($this->handle);
  }

  public function execute() {
    $results = [];

    do {
      $status = curl_multi_exec($this->multi, $running);

      if ($running) {
        curl_multi_select($this->multi);
      }
    } while ($running && $status === CURLM_OK);

    foreach ($this->requests as $request) {
      $buffer = curl_multi_getcontent($request->handle());
      $results = array_merge($results, $request->parse($buffer));

      curl_multi_remove_handle($this->multi, $request->handle());
    }

    return $results;
  }

}
