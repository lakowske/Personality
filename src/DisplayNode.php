<?php
require_once('Template.php');
require_once('ControllerNode.php');

class DisplayNode extends ControllerNode
{
  private $requestPredicate;
  private $templateName;
  private $templateSupplier;

  public function __construct($pattern, $requestPredicate, $templateName, $templateSupplier) {
    parent::__construct($pattern);
    $this->requestPredicate = $requestPredicate;
    $this->templateName = $templateName;
    $this->templateSupplier = $templateSupplier;
  }

  public function evaluate($request) {
    $f = $this->requestPredicate;
    return parent::evaluate($request) && $f($request);
  }
  
  public function run($request) {
    $h = $this->templateSupplier->get($this->templateName);
    return $h->display();
  }

}

?>