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

  public function __construct($commentManager, $commentsHtmlDisplay, $predicate) {
    parent::__construct($predicate, $this);
    $this->commentManager = $commentManager;
    $this->commentsHtmlDisplay = $commentsHtmlDisplay;
  }

  public function run($request) {
    $pathManager = new PathManager($request->getServerVars());
    $base = $pathManager->scriptBasePath();

    $cids = $this->commentManager->last_entries();
    return $this->commentsHtmlDisplay->run($cids, $base);
  }

}

?>