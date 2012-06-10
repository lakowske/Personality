<?php
require_once('Comment.php');

class CommentManager
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
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
     $d = $this->databaseSupplier->get();
     $d->query("BEGIN");
     
     $d->query("delete from comment where cid = '$cid'");
     
     $d->query("COMMIT");
  }

  /*
   * last_entries returns the comment ids of the most recent entries.
   */
  function last_entries() {
     $d = $this->databaseSupplier->get();
     
     $d->query("select cid from comment order by cdate");
   
     $cids = array();
     foreach($d->fetch_all() as $result) {
       array_push($cids, $result['cid']);
     }
     
     return $cids;
  }

  function last_entries_of_type($type) {
     $d = $this->databaseSupplier->get();
     
     $d->query("select cid from comment where type = '$type' order by cdate");
   
     $cids = array();
     foreach($d->fetch_all() as $result) {
       array_push($cids, $result['cid']);
     }
     
     return $cids;
  }    
}

?>
