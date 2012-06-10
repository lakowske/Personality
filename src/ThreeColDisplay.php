<?php

class ThreeColDisplay extends Node
{
  private $left;
  private $center;
  private $right;
  private $title;

  public function __construct($predicate, $templateSupplier, $title, $left, $center, $right) {
    parent::__construct($predicate, $this);
    $this->templateSupplier = $templateSupplier;
    $this->title = $title;
    $this->left = $left;
    $this->center = $center;
    $this->right = $right;
  }
    
  public function run($request) {
    $h = $this->templateSupplier->get('three_col_layout.tpl');
    $h->add_variable('title', $this->title);
    $h->add_variable('left_col', $this->left->run($request));
    $h->add_variable('center_col', $this->center->run($request));
    $h->add_variable('right_col', $this->right->run($request));
    return $h->fetch();
  }

}

?>