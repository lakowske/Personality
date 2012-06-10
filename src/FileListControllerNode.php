<?php
require_once('Node.php');
/**
 * FileListControllerNode retrieves a list of files for a user.
 */
class FileListControllerNode extends Node
{
  private $uploadControllerNode;
  private $fileManager;
  private $userSupplier;

  public function __construct($userSupplier, $fileManager, $predicate) {
    parent::__construct($predicate, $this);

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

    $scriptBasePath = $request->getPathManager()->scriptBasePath();
    $arr = $this->fileManager->get_user_files($user->uid);
    $resultString = "<table>";
    foreach ($arr as $row) {
      $resultString .= "<tr><td><a href='$scriptBasePath/file/{$row['filename']}'>{$row['origname']}</a></td></tr>";
    }
    $resultString .= "</table>";

    return $resultString;
  }

}
?>