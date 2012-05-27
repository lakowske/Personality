<?php
require_once('CommentRetrieverNode.php');
require_once('PathManager.php');

/**
 * CommentsHtmlDisplay turns comment ids into the comments html representation.
 */
class CommentsHtmlDisplay extends CommentRetrieverNode
{
  private $commentHtmlDisplay;

  public function __construct($commentHtmlDisplay) {
    $this->commentHtmlDisplay = $commentHtmlDisplay;
  }

  /*
   * Turns comment ids to html represntations of the comments.
   *
   * $cids - array of comment ids
   * $base - base application path string
   */
  public function run($cids, $base) {
    $result = '';
    foreach ($cids as $cid) {
      $result .= $this->commentHtmlDisplay->run($cid, $base);
    }
    return $result;
  }

}

?>