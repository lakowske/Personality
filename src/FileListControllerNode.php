<?php
require_once('ControllerNode.php');
/**
 * FileListControllerNode retrieves a list of files for a user.
 */
class FileListControllerNode extends ControllerNode
{
  private $uploadControllerNode;
  private $fileManager;
  private $userSupplier;

  public function __construct($userSupplier, $fileManager) {
    parent::__construct("/^\/filelist/");
    $this->userSupplier = $userSupplier;
    $this->fileManager = $fileManager;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isGet();
  }
  
  public function run($request) {
    //require a user to upload files
    $user = $this->userSupplier->get();
    if (!$user) {
      return;
    }

    $arr = $this->fileManager->get_user_files($user->uid);
    $resultString = "<table>";
    foreach ($arr as $row) {
      $resultString .= "<tr><td><a href='file/{$row['filename']}'>{$row['origname']}</a></td></tr>";
    }
    $resultString .= "</table>";

    return $resultString;
  }

}
?>