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
         if($user->gid == 1) {
            $links = "<a href=\"/index/editentry/$cid\">";
            $links .= "Edit</a> |";
            $links .= "<a href=\"/index/deleteentry/$cid\">";
            $links .= "Delete</a> |";
            $template->add_variable('entry_actions', $links);
         }
    } else {
      $links = '';
      $links .= "<a href=\"$base/viewchildren/$cid\">";
      $links .= "View Replies($comment->children)</a> |";
      $links .= "<a href=\"$base/addentry/$cid\">";
      $links .= " Reply</a>";
      $template->add_variable('entry_actions', $links);
      //$template->add_variable('entry_actions', $this->formatted_date($cid));      
    }
    
    $template->add_variable('title', $comment->title);
    $template->add_variable('content', $comment->content);
    $template->add_variable('username', $comment->username);

    return $template->fetch();
  }
  
  /*
  function formatted_date($cid) {
    $d = $this->databaseSupplier->get();
    $d->query("BEGIN");
    $r = $d->query("select to_char(cdate, 'MM/DD/YYYY HH12:MI:SS PM') "
		   . "from comment where cid='$cid'");

    $d->query("COMMIT");
    $row = $d->fetch_row();

    return $row[0];
    
  } 
  */      

}

?>