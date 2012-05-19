<?php

class ControllerTree
{
  private $controllerNodes;

  public function __construct(&$controllerNodes) {
    $this->controllerNodes = $controllerNodes;
  }

  public function setControllerNodes(&$controllerNodes) {
    $this->controllerNodes = $controllerNodes;
  }

  public function evaluate($request) {
    return true;
  }

  public function run($request) {
    $result = "";
    foreach($this->controllerNodes as $controllerNode) {

      $run = $controllerNode->evaluate($request);

      if($run) {
	$result .= $controllerNode->run($request);
      }

    }
    return $result;
  }

}

?>