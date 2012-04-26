<?php

class ControllerNode 
{
  private $pattern;

  public function __construct($pattern) {
    $this->pattern = $pattern;
  }

  public function evaluate($request) {
    return preg_match($this->pattern, $request->getRequestURI()) > 0;
  }

  public function run($request) {
    return;
  }

}

?>