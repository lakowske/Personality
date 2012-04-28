<?php
require_once('ControllerNode.php');

/**
 * UploadControllerNode stores file uploads to an upload directory.
 */
class UploadControllerNode extends ControllerNode
{
  private $uploadDir;

  public function __construct($uploadDir) {
    parent::__construct("/upload$/");
    $this->uploadDir = $uploadDir;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isPost();
  }

  public function run($request) {
    if ($_FILES['datafile']['error'] > 0) {
      error_log("Return Code: " . $_FILES['file']['error']);
    }
    
    move_uploaded_file($_FILES['datafile']['tmp_name'], $this->uploadDir . $_FILES['datafile']['name']);
    error_log("stored file: " . $this->uploadDir . $_FILES['datafile']['name']);
  }
}

?>
