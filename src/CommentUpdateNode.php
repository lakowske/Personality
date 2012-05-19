<?php
require_once('Node.php');
require_once('CommentAddNode.php');

/**
 * CommentUpdateNode updates comments.
 */
class CommentUpdateNode extends Node
{
  private $commentManager;
  private $userSupplier;

  public function __construct($commentManager, $predicate) {
    parent::__construct($predicate, $this);
    $this->commentManager = $commentManager;
  }

  public function run($request) {
    $f = commentConstructor($request);
    $comment = $f($request);
    $this->commentManager->update_entry($comment);
  }
}

?>