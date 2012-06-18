<?php


class Comment
{
   public $cid = Null;
   public $title = '';
   public $content = '';
   public $uid = '';
   public $username = '';
   public $type = '';
   public $rcid = 1;
   public $children = array();

   function marshal() {
     $this->title = pg_escape_string($this->title);
     $this->contents = pg_escape_string($this->content);
   }

}
?>