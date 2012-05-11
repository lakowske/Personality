<?php

class Node
{

  private $predicate;
  private $runnable;

  public function __construct($predicate, $runnable) {
    $this->predicate = $predicate;
    $this->runnable  = $runnable;
  }

  public function evaluate($request) {
    $p = $this->predicate;
    return $p($request);
  }

  public function run($request) {
    $r = $this->runnable;
    return $r($request);
  }

}