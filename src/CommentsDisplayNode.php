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
  private $filterFunction;

  public function __construct($commentManager, $commentsHtmlDisplay, $filterFunction, $predicate) {
    parent::__construct($predicate, $this);
    $this->commentManager = $commentManager;
    $this->commentsHtmlDisplay = $commentsHtmlDisplay;
    $this->filterFunction = $filterFunction;
  }

  public function run($request) {
    $pathManager = new PathManager($request->getServerVars());
    $base = $pathManager->scriptBasePath();
    
    $f = $this->filterFunction;
    $cids = $f();

    return $this->commentsHtmlDisplay->run($cids, $base);
  }

}

?>