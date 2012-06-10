<?php

function commentDelete($commentManager, $regPredicate) {
  return function ($request) use ($commentManager, $regPredicate) {
    $result = $regPredicate->getMatches();
    $cid = $result[1][0];
    $commentManager->delete_entry($cid);
  };
}


/**
 * CommentEditNode displays an editable comment from the database.
 */
class CommentEditNode extends CommentRetrieverNode
{
  private $templateSupplier;

  public function __construct($commentManager, $templateSupplier, $regPredicate, $predicate) {
    parent::__construct($commentManager, $regPredicate, $predicate);
    $this->templateSupplier = $templateSupplier;
  }

  public function run($request) {
    $comment = parent::run($request);
    $pathManager = new PathManager($request->getServerVars());
    $cid = $comment->cid;
    $template = $this->templateSupplier->get('addentry.tpl');
    $base = $pathManager->scriptBasePath();

    $template->add_variable('title', $comment->title);
    $template->add_variable('content', $comment->content);
    $template->add_variable('username', $comment->username);
    $template->add_variable('cid', $comment->cid);
    $template->add_variable('actionlink', "$base/edit/comment/{$cid}");
    return $template->fetch();
  }

}

?>