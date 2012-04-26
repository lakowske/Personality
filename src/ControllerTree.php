<?php

class ControllerTree
{
  private $controllerNodes;

  public function __construct($controllerNodes) {
    $this->controllerNodes = $controllerNodes;
  }

  public function handleRequest($request) {
    foreach($this->controllerNodes as $controllerNode) {

      $result = $controllerNode->evaluate($request);

      if($result) {
	$controllerNode->run($request);
      }

    }
  }

}

?>