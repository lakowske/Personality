<?php
require_once('ControllerNode.php');

/**
 * DownloadControllerNode fullfills download requests.
 */

class DownloadControllerNode extends ControllerNode
{
  private $fileManager;

  public function __construct($fileManager) {
    parent::__construct("/^\/file/");
    $this->fileManager = $fileManager;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isGet();
  }

  public function run($request) {
    $server = $request->getServerVars();
    $path_info = $server['PATH_INFO'];
    $result = preg_match_all('/^\/file\/(.*)/', $path_info, $arr, PREG_PATTERN_ORDER);
    $filename = $arr[1][0];
    $r = $this->fileManager->get_file($filename);
    if ($r == NULL) {
      return FALSE;
    }
    $filepath = $r[2];
    
    if ($fd = fopen($filepath, "r")) {
      $fsize = filesize($filepath);
      $path_parts = pathinfo($filepath);
      header("Content-type: application/octet-stream");
      header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
      header("Content-length: $fsize");
      header("Cache-control: private"); //use this to open files directly
      while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
      }
      return TRUE;
    }

    return FALSE;
  }
}