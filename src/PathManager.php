<?php

class PathManager
{
  private $server;
  
  public function __construct($server) {
    $this->server = $server;
  }

  public static function get() {
    return new PathManager(NULL);
  }

  /**
   * Returns the base path url to the script.
   * i.e.
   * /~user/index/blah/blah -> /~user/index
   *
   */
  public function scriptBasePath() {
    return $this->buildScriptBasePath($this->server);
  }

  public static function buildScriptBasePath($server) {
    $scriptName = $server['SCRIPT_NAME'];
    return strstr($scriptName, '.php', TRUE);
  }

  public function templateDir() {
    $templateDir = __DIR__ . "/../templates";
    return $templateDir;
  }

}