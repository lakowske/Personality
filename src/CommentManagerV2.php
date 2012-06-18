<?php
require_once('Comment.php');

class CommentManagerV2
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }

  function load_entry($cid) {
    $d = $this->databaseSupplier->get();
    
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

    $r = $d->query("select cid from commentref where rcid = '$cid'");

    if (num_rows($r) == 0) {
      return $comment;
    }

    if(num_rows($r) < 0) {
      error_log("Got an error requesting $cid s children");
      return NULL;
    }
    
    $cids = array();
    foreach($d->fetch_all() as $result) {
      array_push($cids, $result['cid']);
    }
    $comment->children = $cids;

    return $comment;
  }

  /**
   * Stores the comment in the database.  Does not concern itself with relations to the comment.
   */
  function copy_entry($comment) {
    $d = $this->databaseSupplier->get();
    
    $d->query("BEGIN");
    
    $comment->marshal();
    
    $d->query("insert into comment (cid, uid, username, title, contents, "
	      . "cdate, type) values('$comment->cid', '$comment->uid', "
	      . "'$comment->username', "
	      . "'$comment->title', '$comment->contents', current_timestamp, "
	      . "'$comment->type')");

    $d->query("COMMIT");
  }

  /**
   * create a refrence to rcid from cid.
   */
  function reference_comment($cid, $rcid) {
    $d = $this->databaseSupplier->get();
    $d->query("BEGIN");
    $d->query("insert into commentref (cid, rcid) values ('$cid', '$rcid')");
    $d->query("COMMIT");
  }

  
  function insert_entry($comment) {
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
     $d = $this->databaseSupplier->get();
     $d->query("BEGIN");
     
     $d->query("delete from comment where cid = '$cid'");
     
     $d->query("COMMIT");
  }

  function filter_comments($sql) 
  {
     $d = $this->databaseSupplier->get();

     $d->query($sql);
   
     $cids = array();
     foreach($d->fetch_all() as $result) {
       array_push($cids, $result['cid']);
     }
     
     return $cids;
  }

  function all_entries() {
    return filter_comments("select cid from comment");
  }

  /*
   * last_entries returns the comment ids of the most recent entries.
   */
  function last_entries() {
    return filter_comments("select cid from comment order by cdate");
  }

  function last_entries_of_type($type) {
    return filter_comments("select cid from comment where type = '$type' order by cdate");
  }    
}

?>
