<?php
require_once('Comment.php');

class CommentManager
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }

   function load_entry() {
     $d = $databaseSupplier->get();
     $r = $d->query("BEGIN");
    
     $r = $d->query("select * from comment where cid = '$this->cid'");
     if(num_rows($r) <= 0) {
       error_log("Got a request for an invalid comment: cid=$this->cid");
       return 0;
     }
      
     $comment = new Comment();
     $row = pg_fetch_row($r);
     $comment->uid      = $row[1];
     $comment->username = $row[2];
     $comment->title    = $row[3];
     $comment->contents = $row[4];
     $comment->tstamp   = $row[5];
     $comment->type     = $row[8];
     
     $r = $d->query("select count(*) from comment where rcid = '$comment->cid'");
     
     if(num_rows($r) <= 0) {
       $comment->children = 0;
     } else {
       $row = pg_fetch_row($r);      
       $comment->children = $row[0];
     }
    
     $r = $d->query("COMMIT");    
      
     return $comment;
   }
  
   function store_entry($comment) {
     $d = $databaseSupplier->get();
     
     $d->query("BEGIN");

     $comment->marshal();
    
     $d->query("insert into comment (uid, username, title, contents, "
		     . "cdate, rcid, rsid, type) values('$comment->uid', "
		     . "'$comment->username', "
		     . "'$comment->title', '$comment->contents', current_timestamp, "
		     . "'$comment->rcid', '$comment->rsid', '$comment->type')");
     
     $d->query("COMMIT");
   }

   function update_entry($comment) {
     $d = $databaseSupplier->get();
     $d->query("BEGIN");

     $comment->marshal();

     $d->query("update comment set uid='$comment->uid', "
	       . "username='$comment->username', "
	       . "title='$comment->title', contents='$comment->contents', "
	       //. "cdate='$comment->tstamp', "
	       . "rcid='$comment->rcid', rsid='$comment->rsid', "
	       . "type='$comment->type' "
	       . "where cid = '$comment->cid'"
	       );
    
      $d->query("COMMIT");
   }

   function delete_entry($comment) {
     $d = $databaseSupplier->get();
     $d->query("BEGIN");
    
     $d->query("delete from comment where cid = '$comment->cid'");
    
     $d->query("COMMIT");
   }
  
}

?>




}