<?php
require_once('CommentRetrieverNode.php');
require_once('PathManager.php');

/**
 * CommentDisplayNode displays comments.
 */
class CommentsDisplayNode extends Node
{
  private $commentManager;
  private $commentsHtmlDisplay;
  private $type;

  public function __construct($type, $commentManager, $commentsHtmlDisplay, $predicate) {
    parent::__construct($predicate, $this);
    $this->type = $type;
    $this->commentManager = $commentManager;
    $this->commentsHtmlDisplay = $commentsHtmlDisplay;
  }

  public function run($request) {
    $pathManager = new PathManager($request->getServerVars());
    $base = $pathManager->scriptBasePath();

    $cids = $this->commentManager->last_entries_of_type($this->type);
    return $this->commentsHtmlDisplay->run($cids, $base);
  }

}

?>