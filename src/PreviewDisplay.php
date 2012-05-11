<?php

class PreviewDisplay
{
  private $templateName;
  private $templateSupplier;

  public function __construct($templateName, $templateSupplier) {
    $this->templateName = $templateName;
    $this->templateSupplier = $templateSupplier;
  }
  
  public function run($request) {
    $postVars = &$request->getPostVars();
    $t = $this->templateSupplier->get($this->templateName);
    foreach ($postVars as $key => $value) {
      $t->add_variable($key, $value);
    }
    return $t->display();
  }
}

?>