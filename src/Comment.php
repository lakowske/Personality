<?php


class Comment extends Template
{
   public $cid = Null;
   public $title = '';
   public $contents = '';
   public $uid = '';
   public $username = '';
   public $type = '';
   public $rcid = 1;
   public $rsid = 1;


   function marshal() {
     $this->contents = pg_escape_string($this->contents);
   }

}
?>