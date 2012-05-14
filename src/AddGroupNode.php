<?php
require_once('ControllerNode.php');

/**
 * AddGroupNode creates a new group.
 */
class AddGroupNode extends ControllerNode
{
  private $groupManager;
  
  public function __construct($groupManager) {
    parent::__construct("/^\/addgroup/");
    $this->groupManager = $groupManager;
  }

  public function evaluate($request) {
    return parent::evaluate($request) && $request->isPost();
  }

  public function run($request) {
    $postVars = $request->getPostVars();
    $groupname = $postVars['groupname'];
    $description = $postVars['description'];
    $this->groupManager->addGroup($groupname, $description);
  }
}

?>
