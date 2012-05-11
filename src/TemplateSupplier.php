<?php
require_once('Template.php');

class TemplateSupplier
{
  private $dir;
  private $templateDirs;

  public function __construct($dir, $templateDirs) {
    $this->dir = $dir;
    $this->templateDirs = $templateDirs;
  }

  public function get($template) {
    $t = new Template();
    $t->setDir($this->dir);
    $t->setTemplateDirs($this->templateDirs);
    $t->setTemplate($template);
    return $t;
  }

}
?>