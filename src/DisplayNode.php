<?php
require_once('Template.php');
require_once('ControllerNode.php');

function displayFunc($templateName, $templateSupplier) {
  return function ($request) use ($templateSupplier, $templateName) {
    $t = getTemplateFunc($templateName, $templateSupplier);
    return $t($request)->display();
  };
}

function previewFunc($templateName, $templateSupplier) {
  return function ($request) use ($templateSupplier, $templateName) {
    $tf = getTemplateFunc($templateName, $templateSupplier);
    $t = $tf($request);
    $postVars = $request->getPostVars();
    foreach ($postVars as $key => $value) {
      $t->add_variable($key, $value);
    }
    return $t->display();
  };
}
  
function getTemplateFunc($templateName, $templateSupplier) {
  return function ($request) use ($templateSupplier, $templateName) {
    $h = $templateSupplier->get($templateName);
    return $h;
  };
}

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