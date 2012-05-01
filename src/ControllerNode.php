<?php

class ControllerNode 
{
  private $pattern;

  public function __construct($pattern) {
    $this->pattern = $pattern;
  }

  public function evaluate($request) {
    $server = $request->getServerVars();
    return preg_match($this->pattern, $server['PATH_INFO']) > 0;
  }

  public function run($request) {
    return;
  }

}

?>