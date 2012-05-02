<?php

require_once('Smarty.class.php');

class Template {
  var $smarty = NULL;
  var $filename = '';
  var $dir = '';

  function Template() {
    $this->smarty = new Smarty;
  }

  function setTemplate($filename) {
    $this->filename = $filename;
  }

  function setDir($dir) {
    $this->dir = $dir;
  }

  function add_variable($var_name, $var_value) {
    $this->smarty->assign($var_name, $var_value);
  }
  
  function display() {
    $prefix = $this->dir;
    $smarty->template_dir = $prefix . '/templates';
    $smarty->compile_dir = '/tmp';
    $smarty->config_dir = $prefix . '/configs';
    $smarty->cache_dir = $prefix . '/cache';
    $this->smarty->display($this->filename);
  }

  function fetch() {
    return $this->smarty->fetch($this->filename);
  }
  
}

?>
