<?php

require_once('site_config.php');
require_once('Template.php');


class Entry extends Template
{
   var $d;
   var $cid = Null;
   var $title = '';
   var $contents = '';
   var $uid = '';
   var $username = '';
   var $type = '';
   var $rcid = 1;
   var $rsid = 1;

   function Entry($cid = -1) {
      Template::Template('entry.tpl');
      $this->d = new Database();
      $this->cid = $cid;

      if($this->cid != -1) {
         $this->load_entry();
      }

      //    echo "<font color=\"#ffffff\">$cid</font>";

      $this->add_variables();
   }

   function load_entry() {
      $r = $this->d->query("BEGIN");
    
      $r = $this->d->query("select * from comment where cid = '$this->cid'");
      if(num_rows($r) <= 0) {
         error_log("Got a request for an invalid comment: cid=$this->cid");
         return 0;
      }

      $row = pg_fetch_row($r);
      $this->uid      = $row[1];
      $this->username = $row[2];
      $this->title    = $row[3];
      $this->contents = $row[4];
      $this->tstamp   = $row[5];
      $this->type     = $row[8];
      error_log("load entry");
      error_log($this->tstamp);
      error_log($this->type);
      
      $r = $this->d->query("select count(*) from comment where rcid = '$this->cid'");

      if(num_rows($r) <= 0) {
         $this->children = 0;
      } else {
         $row = pg_fetch_row($r);      
         $this->children = $row[0];
      }
    
      $r = $this->d->query("COMMIT");    
   }

   function marshal() {
     $this->contents = pg_escape_string($this->contents);
   }

   function store_entry() {
      $this->d->query("BEGIN");

      $this->marshal();
    
      $this->d->query("insert into comment (uid, username, title, contents, "
                      . "cdate, rcid, rsid, type) values('$this->uid', "
                      . "'$this->username', "
                      . "'$this->title', '$this->contents', current_timestamp, "
                      . "'$this->rcid', '$this->rsid', '$this->type')");
    
      $this->d->query("COMMIT");
   }

   function update_entry() {
      $this->d->query("BEGIN");

      error_log("update_entry()");
      $this->marshal();
      error_log($this->tstamp);
      $this->d->query("update comment set uid='$this->uid', "
                      . "username='$this->username', "
                      . "title='$this->title', contents='$this->contents', "
                      //. "cdate='$this->tstamp', "
                      . "rcid='$this->rcid', rsid='$this->rsid', "
                      . "type='$this->type' "
                      . "where cid = '$this->cid'"
                      );
    
      $this->d->query("COMMIT");
   }

   function delete_entry() {
      $this->d->query("BEGIN");
    
      $this->d->query("delete from comment where cid = '$this->cid'");
    
      $this->d->query("COMMIT");
   }
  
   function new_entry() {
    
   }

   function add_variables() {
      if(array_key_exists('user', $_SESSION)) {
         error_log('Entry() user key exists');
         $u = unserialize($_SESSION['user']);
         error_log("user gid = $u->gid");
         if($u->gid == 1) {
            $links = "<a href=\"index.php?op=edit_entry&cid=$this->cid\">";
            $links .= "Edit</a> |";
            $links .= "<a href=\"index.php?op=delete_entry&cid=$this->cid\">";
            $links .= "Delete</a> |";
            $this->add_variable('entry_actions', $links);           
         } 
      } else {
         $links .= "<a href=\"index.php?op=view_children&cid=$this->cid\">";
         $links .= "View Replies($this->children)</a> |";
         $links .= "<a href=\"index.php?op=add_entry&reply_cid=$this->cid\">";
         $links .= " Reply</a>";
         //$this->add_variable('entry_actions', $links);
         $this->add_variable('entry_actions', $this->formatted_date());      
      }

      $this->add_variable('title', $this->title);
      $this->add_variable('contents', $this->contents);
      $this->add_variable('username', $this->username);
   }

   function formatted_date() {
      $this->d->query("BEGIN");
      $r = $this->d->query("select to_char(cdate, 'MM/DD/YYYY HH12:MI:SS PM') "
                           . "from comment where cid='$this->cid'");

      $row = pg_fetch_row($r);

      return $row[0];
      $this->d->query("COMMIT");
   }       
}

class EditEntry extends Entry
{
   function EditEntry($cid = -1) {
      Template::Template('add_entry.tpl');
      $this->d = new Database();
      $this->cid = $cid;

      if($this->cid != -1) {
         $this->load_entry();
      }

      //    echo "<font color=\"#ffffff\">$cid</font>";

      $this->add_variables();
      $this->add_variable('cid', $this->cid);
   }
}

?>