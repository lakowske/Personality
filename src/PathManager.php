<?php

class PathManager
{
  private $scriptBasePath;

  public function __construct($scriptBasePath) {
    $this->scriptBasePath = $scriptBasePath;
  }

  public static function get() {
    return new PathManager(NULL);
  }

  public static function buildFromServer($server) {
    return new self(PathManager::buildScriptBasePath($server));
  }
    

  /**
   * Returns the base path url to the script.
   * i.e.
   * /~user/index/blah/blah -> /~user/index
   *
   */
  public function scriptBasePath() {
    return $this->scriptBasePath;
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