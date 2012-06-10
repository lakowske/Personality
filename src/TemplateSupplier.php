<?php
require_once('Template.php');

class TemplateSupplier
{
  private $dir;
  private $templateDirs;

  public function __construct($dir, $templateDirs, $staticBasePath) {
    $this->dir = $dir;
    $this->templateDirs = $templateDirs;
    $this->staticBasePath = $staticBasePath;
  }

  public function get($template) {
    $t = new Template();
    $t->setDir($this->dir);
    $t->setTemplateDirs($this->templateDirs);
    $t->setTemplate($template);
    $t->add_variable('staticBasePath', $this->staticBasePath);
    return $t;
  }

}
?>