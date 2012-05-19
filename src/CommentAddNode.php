<?php
require_once('Node.php');

function commentConstructor() {
  return function ($request) {
    $postVars = $request->getPostVars();
    $c = new Comment();
    $vars = get_object_vars($c);
    foreach ($vars as $key => $value) {
      $v = $postVars[$key];
      $c->$key = $v;
    }
    return $c;
  };
}

/**
 * CommentAddNode adds comments to the database.
 */
class CommentAddNode extends Node
{
  private $commentManager;
  private $userSupplier;

  public function __construct($commentManager, $userSupplier, $predicate) {
    parent::__construct($predicate, $this);
    $this->commentManager = $commentManager;
    $this->userSupplier = $userSupplier;
  }

  public function run($request) {
    $f = commentConstructor($request);
    $comment = $f($request);
    
    $user = $this->userSupplier->get();
    $comment->uid = $user->uid;
    $comment->username = $user->username;
    
    $this->commentManager->store_entry($comment);
  }
}

?>