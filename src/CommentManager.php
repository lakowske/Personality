<?php
require_once('Comment.php');

function byType($commentManager, $type) {
  return function () use ($commentManager, $type) {
    return $commentManager->last_entries_of_type($type);
  };
}

function byReference($commentManager, $cid) {
  return function () use ($commentManager, $cid) {
    return $commentManager->last_entries_referencing($cid);
  };
}

class CommentManager
{
  private $databaseSupplier;
  private $commentRefManager;

  public function __construct($databaseSupplier, $commentRefManager) {
    $this->databaseSupplier = $databaseSupplier;
    $this->commentRefManager = $commentRefManager;
  }

  function load_entry($cid) {
    $d = $this->databaseSupplier->get();
    $r = $d->query("BEGIN");
    
    $r = $d->query("select * from comment where cid = '$cid'");
    if(num_rows($r) <= 0) {
      error_log("Got a request for an invalid comment: cid=$cid");
      return 0;
    }
    
    $comment = new Comment();
    $row = pg_fetch_row($r);
    $comment->cid      = $row[0];
    $comment->uid      = $row[1];
    $comment->username = $row[2];
    $comment->title    = $row[3];
    $comment->content  = $row[4];
    $comment->type     = $row[5];
    $comment->tstamp   = $row[6];

    /*
    $r = $d->query("select count(*) from comment where rcid = '$comment->cid'");
    
    if(num_rows($r) <= 0) {
      $comment->children = 0;
    } else {
      $row = pg_fetch_row($r);      
      $comment->children = $row[0];
    }

    $r = $d->query("COMMIT");    
    */    
    return $comment;
  }
  
  function store_entry($comment) {
    $d = $this->databaseSupplier->get();
    
    $d->query("BEGIN");
    
    $comment->marshal();
    
    $d->query("insert into comment (uid, username, title, contents, "
	      . "cdate, type) values('$comment->uid', "
	      . "'$comment->username', "
	      . "'$comment->title', '$comment->contents', current_timestamp, "
	      . "'$comment->type')");
    
    $d->query("COMMIT");
  }
  
  function update_entry($comment) {
    $d = $this->databaseSupplier->get();
    $d->query("BEGIN");
    
    $comment->marshal();
    
    $d->query("update comment set "
	      . "username='$comment->username', "
	      . "title='$comment->title', contents='$comment->contents', "
	      //. "cdate='$comment->tstamp', "
	      . "type='$comment->type' "
	      . "where cid = '$comment->cid'"
	      );
    
    $d->query("COMMIT");
  }
  
  function delete_entry($cid) {
    //Delete the reference from this comment to any other.
    $this->commentRefManager->removeCommentReferences($cid);

    $d = $this->databaseSupplier->get();

    $d->query("BEGIN");
     
    $d->query("delete from comment where cid = '$cid'");
     
    $d->query("COMMIT");
  }

  /*
   * last_entries returns the comment ids of the most recent entries.
   */
  function last_entries() {
    return filter_comments("select cid from comment order by cdate");
  }

  function filter_comments($sql) {
     $d = $this->databaseSupplier->get();
     error_log($sql);
     $d->query($sql);
   
     $cids = array();
     
     if ($d->num_rows() < 1) {
       return $cids;
     }

     foreach($d->fetch_all() as $result) {
       array_push($cids, $result['cid']);
     }
     
     return $cids;
  }

  /*
   * cid - comment id
   * returns comments referencing cid
   */
  function last_entries_referencing($cid) {
    return $this->filter_comments("select c.cid from commentref r, comment c where r.rcid = '$cid' and r.cid = c.cid order by c.cdate desc");
  }

  function last_entries_of_type($type) {
    return $this->filter_comments("select cid from comment where type = '$type' order by cdate");
  }    
}

?>
