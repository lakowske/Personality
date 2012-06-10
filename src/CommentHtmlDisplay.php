<?php
require_once('CommentRetrieverNode.php');
require_once('PathManager.php');

/**
 * CommentHtmlDisplayNode returns a html formatted comment.
 */
class CommentHtmlDisplayNode extends CommentRetrieverNode
{
  private $userSupplier;
  private $templateSupplier;
  private $databaseSupplier;
  private $commentManager;

  public function __construct($commentManager, $userSupplier, $templateSupplier, $databaseSupplier) {
    $this->commentManager = $commentManager;
    $this->userSupplier = $userSupplier;
    $this->templateSupplier = $templateSupplier;
    $this->databaseSupplier = $databaseSupplier;
  }

  public function run($cid, $base) {
    $comment = $this->commentManager->load_entry($cid);

    $template = $this->templateSupplier->get('entry.tpl');
    $user = $this->userSupplier->get();

    if ($user != null) {
      $links = '';
      $links .= "<a href=\"$base/edit/comment/$cid\">";
      $links .= "Edit</a> |";
      $links .= "<a href=\"$base/delete/comment/$cid\">";
      $links .= "Delete</a> |";
      $template->add_variable('entry_actions', $links);
    } else {
      $links = '';
      $links .= "<a href=\"$base/viewchildren/$cid\">";
      $links .= "View Replies($comment->children)</a> |";
      $links .= "<a href=\"$base/create/comment/$cid\">";
      $links .= " Reply</a>";
      //$template->add_variable('entry_actions', $links);
      $template->add_variable('entry_actions', $this->formatted_date($cid));
    }
    
    $template->add_variable('title', $comment->title);
    $template->add_variable('content', $comment->content);
    $template->add_variable('username', $comment->username);

    return $template->fetch();
  }
  

  function formatted_date($cid) {
    $d = $this->databaseSupplier->get();
    
    $r = $d->query("select to_char(cdate, 'MM/DD/YYYY HH12:MI:SS PM') "
    		   . "from comment where cid='$cid'");

    $row = $d->fetch_row();
    return $row[0];
  } 


}

?>