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

    if (file_exists($filepath)) {
      $fsize = filesize($filepath);
      $path_parts = pathinfo($filepath);
      $file_type = $this->fileTypeManager->filetype($filepath);
      header("Content-Description: File Transfer");
      header("Content-Type: $file_type");
      header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
      header("Expires: 0");
      error_log("file path: $filepath");
      error_log("Content-length: $fsize");

      header("Content-Length: $fsize");
      header("Cache-Control: must-revalidate"); //use this to open files directly
      ob_clean();
      flush();
      readfile($filepath);
      exit(0);
      return TRUE;
    }

    return FALSE;
  }
}