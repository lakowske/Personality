<?php
require_once('site_config.php');
require_once('Smarty.class.php');


class Template {
  var $smarty = NULL;
  var $filename = '';
  
  function Template($filename) {
    global $hostname, $ossse_home;
    $smarty = new Smarty;

    $prefix = $GLOBALS['prefix'];
    $smarty->template_dir = $prefix . '/templates';
    $smarty->compile_dir = '/tmp';
    $smarty->config_dir = $prefix . '/configs';
    $smarty->cache_dir = $prefix . '/cache';

    $smarty->assign('hostname', $GLOBALS['hostname']);


    $this->filename = $filename;
    $this->smarty = $smarty;
  }

  function add_variable($var_name, $var_value) {
    $this->smarty->assign($var_name, $var_value);
  }
  
  function display() {
    $this->smarty->display($this->filename);
  }

  function fetch() {
    return $this->smarty->fetch($this->filename);
  }
  
}

?>
