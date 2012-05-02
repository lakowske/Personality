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

      $result = $controllerNode->evaluate($request);

      if($result) {
	$result .= $controllerNode->run($request);
      }

    }
    return $result;
  }

}

?>