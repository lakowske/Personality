<?php
require_once('User.php');
require_once('ControllerNode.php');

class LoginControllerNode extends ControllerNode
{
  public $userSupplier = null;

  public function __construct($userSupplier) {
    parent::__construct("/login$/");
    $this->userSupplier = $userSupplier;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isPost();
  }

  public function run($request) {
    $postVars = $request->getPostVars();
    $username = $postVars['username'];
    $password = $postVars['password'];
    $sessionVars = &$request->getSessionVars();

    $u = $this->userSupplier->get();
    if($u->login($username, $password)) {
      error_log("$username login successful");
      $sessionVars['user'] = serialize($u);
    } else {
      error_log("$username login unsuccessful");        
      unset($sessionVars['user']);
      $u = 0;
      error_log("error logging in please try again\n");
    }
  
  }

}

?>