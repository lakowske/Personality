<?php
require_once('Template.php');

class TemplateSupplier
{
  private $dir;

  public function __construct($dir) {
    $this->dir = $dir;
  }

  public function get($template) {
    $t = new Template();
    $t->setDir($this->dir);
    $t->setTemplate($template);
    return $t;
  }

}
?>