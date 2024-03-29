<?php

require_once('Smarty.class.php');

class Template {
  var $smarty = NULL;
  var $filename = '';
  var $dir = '';
  var $templateDirs = array();

  function Template() {
    $this->smarty = new Smarty;
  }

  function setTemplate($filename) {
    $this->filename = $filename;
  }

  function setDir($dir) {
    $this->dir = $dir;
  }

  function setTemplateDirs($templateDirs) {
    $this->templateDirs = $templateDirs;
  }

  function add_variable($var_name, $var_value) {
    $this->smarty->assign($var_name, $var_value);
  }
  
  function setup() {
    $prefix = $this->dir;
    $dirs = array_merge($this->templateDirs, array($prefix . '/templates'));
    $this->smarty->template_dir = $dirs;
  }

  function display() {
    $this->setup();
    $this->smarty->display($this->filename);
  }

  function fetch() {
    $this->setup();
    return $this->smarty->fetch($this->filename);
  }
  
}

?>
