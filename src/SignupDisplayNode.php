<?php
require_once('Template.php');
require_once('ControllerNode.php');

class SignupDisplayNode extends ControllerNode
{

  public function __construct() {
    parent::__construct("/signup$/");
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isGet();
  }

  public function run($request) {
    $h = new Template('signup.tpl');
    return $h->display();
  }

}
?>