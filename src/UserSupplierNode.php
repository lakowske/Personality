<?php
require_once('ControllerTree.php');

class UserSupplierNode extends ControllerTree
{
  private $lastUser;

  public function __construct(&$subnodes) {
    parent::__construct(&$subnodes);
  }

  public function run($request) {
    $session = &$request->getSessionVars();
    $this->lastUser = unserialize($session['user']);
    return parent::run($request);
  }


  public function get() {
    return $this->lastUser;
  }
}

?>