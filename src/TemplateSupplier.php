<?php
require_once('Template.php');

class TemplateSupplier
{
  private $dir;
  private $templateDirs;
  private $scriptBasePath;

  public function __construct($dir, $templateDirs, $staticBasePath, $scriptBasePath) {
    $this->dir = $dir;
    $this->templateDirs = $templateDirs;
    $this->staticBasePath = $staticBasePath;
    $this->scriptBasePath = $scriptBasePath;
  }

  public function get($template) {
    $t = new Template();
    $t->setDir($this->dir);
    $t->setTemplateDirs($this->templateDirs);
    $t->setTemplate($template);
    $t->add_variable('staticBasePath', $this->staticBasePath);
    $t->add_variable('scriptBasePath', $this->scriptBasePath);
    return $t;
  }

}
?>