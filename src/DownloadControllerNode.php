<?php
require_once('ControllerNode.php');
require_once('Node.php');
/**
 * DownloadControllerNode fullfills download requests.
 */

class DownloadControllerNode extends Node
{
  private $fileManager;
  private $fileTypeManager;
  private $regPred;

  public function __construct($fileManager, $fileTypeManager, $regPred, $predicate) {
    parent::__construct($predicate, $this);
    $this->regPred = $regPred;
    $this->fileTypeManager = $fileTypeManager;
    $this->fileManager = $fileManager;
  }

  public function run($request) {
    $result = $this->regPred->getMatches();
    $filename = $result[1][0];
    $r = $this->fileManager->get_file($filename);
    if ($r == NULL) {
      return FALSE;
    }
    $filepath = $r[2];
    
    if ($fd = fopen($filepath, "r")) {
      $fsize = filesize($filepath);
      $path_parts = pathinfo($filepath);
      $file_type = $this->fileTypeManager->filetype($filepath);
      header("Content-type: $file_type");
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