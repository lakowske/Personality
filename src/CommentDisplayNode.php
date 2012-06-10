<?php
require_once('CommentRetrieverNode.php');
require_once('PathManager.php');

/**
 * CommentDisplayNode displays comments.
 */
class CommentDisplayNode extends Node
{
  private $regPredicate;
  private $commentHtmlDisplayNode;

  public function __construct($commentHtmlDisplayNode, $regPredicate, $predicate) {
    parent::__construct($predicate, $this);
    $this->regPredicate = $regPredicate;
    $this->commentHtmlDisplayNode = $commentHtmlDisplayNode;
  }

  public function run($request) {
    $result = $this->regPredicate->getMatches();
    $cid = $result[1][0];

    $pathManager = $request->getPathManager();
    $base = $pathManager->scriptBasePath();    

    return $this->commentHtmlDisplayNode->run($cid, $base);
  }

}

?>