<?php
require_once('Node.php');


/**
 * CommentAddNode displays comments on the database.
 */
class CommentRetrieverNode extends Node
{
  private $commentManager;
  private $regPredicate;

  public function __construct($commentManager, $regPredicate, $predicate) {
    parent::__construct($predicate, $this);
    $this->commentManager = $commentManager;
    $this->regPredicate = $regPredicate;
  }

  public function run($request) {
    $result = $this->regPredicate->getMatches();
    $cid = $result[1][0];

    $comment = $this->commentManager->load_entry($cid);
    return $comment;
  }
}

?>