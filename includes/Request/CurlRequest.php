<?php

class CurlRequest {

  private $handle;

  public function __construct(CurlOptions $options) {
    $this->handle = curl_init();

    $options->apply($this->handle);
  }

  public function __destruct() {
    curl_close($this->handle);
  }

  public function handle() {
    return $this->handle;
  }

  public function send(string $url): string {
    curl_setopt($this->handle, CURLOPT_URL, $url);

    $buffer = curl_exec($this->handle);

    if ($buffer === false) {
      throw new Exception(curl_error($this->handle));
    }

    return $buffer;
  }

}
