<?php
require_once('ControllerNode.php');

class SignupControllerNode extends ControllerNode
{
  private $userManager;

  public function __construct($userManager) {
    parent::__construct("/signup$/");
    $this->userManager = $userManager;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isPost();
  }

  public function run($request) {
    $postVars = $request->getPostVars();
    $username = $postVars['username'];
    $password = $postVars['password'];
    $email = $postVars['email'];

    $this->userManager->add_user($username, $password, $username, $email);
  }
  
}