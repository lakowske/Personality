<?php

function regReqPred($pattern) {
  return function ($request) use ($pattern) {
    $server = $request->getServerVars();
    return preg_match($pattern, $server['PATH_INFO']) > 0;
  };
}      

class Request
{
  private $postVars;
  private $sessionVars;
  private $requestMethod;
  private $serverVars;
  private $pathManager;

  public function __construct(&$postVars, &$sessionVars, &$serverVars, $requestMethod, $pathManager) {
    $this->postVars = &$postVars;
    $this->sessionVars = &$sessionVars;
    $this->requestMethod = $requestMethod;
    $this->serverVars = &$serverVars;
    $this->pathManager = $pathManager;
  }

  public static function fromEnvironmentVariables() {
    $pathManager = PathManager::buildFromServer(&$_SERVER);
    return Request::fromEnvironmentVariablesAndPathManager($pathManager);
  }

  public static function fromEnvironmentVariablesAndPathManager($pathManager) {
    $instance = new self(&$_POST, &$_SESSION, &$_SERVER, $_SERVER['REQUEST_METHOD'], $pathManager);
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

  public function &getPathManager() {
    return $this->pathManager;
  }

}

  
  

?>