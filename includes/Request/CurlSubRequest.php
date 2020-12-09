<?php

class CurlSubRequest {

  private $handle;

  private $parser;

  public function __construct(CurlOptions $options, Parser $parser) {
    $this->handle = curl_init();

    $options->apply($this->handle);
  }

  public function handle() {
    return $this->handle;
  }

  public function parse(string $buffer) {
    return $this->parser->parse($buffer);
  }

}
