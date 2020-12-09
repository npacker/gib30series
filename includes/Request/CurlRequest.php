<?php

class CurlRequest {

  private $handle;

  public function __construct(string $cainfo) {
    $this->handle = curl_init();

    curl_setopt($this->handle, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($this->handle, CURLOPT_FORBID_REUSE, TRUE);
    curl_setopt($this->handle, CURLOPT_ENCODING, '');
    curl_setopt($this->handle, CURLOPT_HEADER, FALSE);
    curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($this->handle, CURLOPT_CAINFO, $cainfo);
  }

  public function __destruct() {
    curl_close($this->handle);
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
