<?php

require_once('site_config.php');
require_once('Template.php');


class Layout extends Template
{
  var $title = "Layout";
  var $left_objs;
  var $left = "";
  var $cen_objs;
  var $cen = "";
  var $right = "";
  var $right_objs;
  
  function Layout($title) {
    Template::Template('three_col_layout.tpl');

    $this->left_objs = array();
    $this->cen_objs = array();
    $this->right_objs = array();    
    
    $this->title = $title;
    $this->load_variables();
  }

  function load_variables() {
    $this->add_variable('title', $this->title);

    //grab the column data
    $this->left = $this->load_column($this->left_objs);
    $this->cen = $this->load_column($this->cen_objs);
    $this->right = $this->load_column($this->right_objs);

    $this->add_variable('left_col', $this->left);
    $this->add_variable('cen_col', $this->cen);    
    $this->add_variable('right_col', $this->right);
  }

  function load_column($col_array) {
    $col_size = sizeof($col_array);
    //    $col = "<table><tbody>";
    
    for($i = 0 ; $i < $col_size ; $i++) {
      //      $col .= "<tr>";
      $o = $col_array[$i];
      $col .= $o->fetch();
            $col .= "<BR>";
      //      $col .= "</tr>";
    }

    //    $col .= "</tbody></table>";
    return $col;
  }
  
  function add_left_col($obj) {
    array_push($this->left_objs, $obj);
  }

  function add_cen_col($obj) {
    array_push($this->cen_objs, $obj);
  }

  function add_right_col($obj) {
    array_push($this->right_objs, $obj);
  }

  
}

     