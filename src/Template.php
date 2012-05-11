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
  
  function display() {
    $prefix = $this->dir;
    $dirs = array_merge(array(), array($prefix . '/templates'));
    
    $smarty->template_dir = $prefix . '/lib/personality-3.0.0/templates';
    echo var_dump($smarty->template_dir);
    //$smarty->compile_dir = '/tmp';
    //$smarty->config_dir = $prefix . '/configs';
    //$smarty->cache_dir = $prefix . '/cache';
    //$smarty->caching = false;
    $this->smarty->display($this->filename);
  }

  function fetch() {
    return $this->smarty->fetch($this->filename);
  }
  
}

?>
