<?php

class CurlRequest {

  private $cainfo;

  public function __construct(string $cainfo) {
    $this->cainfo = $cainfo;
  }

  public function send(string $url): string {
    $handle = curl_init($url);

    curl_setopt($handle, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($handle, CURLOPT_FORBID_REUSE, TRUE);
    curl_setopt($handle, CURLOPT_HEADER, FALSE);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($handle, CURLOPT_CAINFO, $this->cainfo);

    $buffer = curl_exec($handle);

    if ($buffer === false) {
      throw new Exception(curl_error($handle));
    }

    curl_close($handle);

    return $buffer;
  }

}
