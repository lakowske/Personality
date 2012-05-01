<?php

class PathManager
{
  private $server;
  
  public function __construct($server) {
    $this->server = $server;
  }

  /**
   * Returns the base path url to the script.
   * i.e.
   * /~user/index/blah/blah -> /~user/index
   *
   */
  public function scriptBasePath() {
    $scriptName = $this->server['SCRIPT_NAME'];
    return strstr($scriptName, '.php', TRUE);
  }
    

}