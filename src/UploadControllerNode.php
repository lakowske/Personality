<?php
require_once('ControllerNode.php');

/**
 * UploadControllerNode stores file uploads to an upload directory.
 */
class UploadControllerNode extends ControllerNode
{
  private $uploadManager;

  public function __construct($uploadManager) {
    parent::__construct("/upload$/");
    $this->uploadManager = $uploadManager;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isPost();
  }

  public function run($request) {
    if ($_FILES['datafile']['error'] > 0) {
      error_log("Return Code: " . $_FILES['file']['error']);
      return false;
    }
    
    $origname = $_FILES['datafile']['name'];
    $filename = $this->uploadManager->getNewFilename($origname);
    $path = $this->uploadManager->getPath($filename);
    move_uploaded_file($_FILES['datafile']['tmp_name'], $path);
    error_log("stored file: " . $filename);
    return array('origname' => $origname, 'filename' => $filename, 'path' => $path);
  }
}

?>
