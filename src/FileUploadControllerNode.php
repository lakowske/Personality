<?php
require_once('ControllerNode.php');

/**
 * FileUploadControllerNode stores file uploads to an upload directory and makes
 * a file record in the database.
 */
class FileUploadControllerNode extends ControllerNode
{
  private $uploadControllerNode;
  private $fileManager;
  private $uploadManager;
  private $userSupplier;

  public function __construct($uploadControllerNode, $fileManager, $uploadManager, $userSupplier) {
    parent::__construct("/upload$/");
    $this->uploadControllerNode = $uploadControllerNode;
    $this->fileManager = $fileManager;
    $this->uploadManager = $uploadManager;
    $this->userSupplier = $userSupplier;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isPost();
  }

  public function run($request) {
    //require a user to upload files
    $user = $this->userSupplier->get();
    if (!$user) {
      return;
    }
    
    //Handle the file transfer
    $result = $this->uploadControllerNode->run($request);
    if ($result == null) {
      return false;
    }

    //Create the file entry
    $origname = $result['origname'];
    $filename = $result['filename'];
    $filepath = $result['path'];

    $this->fileManager->add_file($origname, $filename, $filepath, $user->uid, $user->gid);
  }
}

?>
