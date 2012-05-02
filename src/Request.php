<?php

class Request
{
  private $postVars;
  private $sessionVars;
  private $requestMethod;
  private $serverVars;

  public function __construct(&$postVars, &$sessionVars, &$serverVars, $requestMethod) {
    $this->postVars = &$postVars;
    $this->sessionVars = &$sessionVars;
    $this->requestMethod = $requestMethod;
    $this->serverVars = &$serverVars;
  }

  public static function fromEnvironmentVariables() {
    $instance = new self(&$_POST, &$_SESSION, &$_SERVER, $_SERVER['REQUEST_METHOD']);
    return $instance;
  }

  public function isPost() {
    return $this->requestMethod == 'POST';
  }

  public function isGet() {
    return $this->requestMethod == 'GET';
  }

  public function getRequestURI() {
    return $this->serverVars['REQUEST_URI'];
  }
  
  public function &getPostVars() {
    return $this->postVars;
  }

  public function &getSessionVars() {
    return $this->sessionVars;
  }

  public function &getServerVars() {
    return $this->serverVars;
  }
}

  
  

?>