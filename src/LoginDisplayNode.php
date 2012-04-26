<?php
require_once('Template.php');
require_once('ControllerNode.php');

class LoginDisplayNode extends ControllerNode
{

  public function __construct() {
    parent::__construct("/login$/");
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isGet();
  }

  public function run($request) {
    $h = new Template('login.tpl');
    return $h->display();
  }

}
?>