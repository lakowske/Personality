<?php
require_once('Database.php');

//global user definitions
$GLOBALS['admin'] = 1;
$GLOBALS['trade_pl'] = 2;
$GLOBALS['tax_man'] = 3;

class User
{
  public $username = 'guest';
  public $uid = -1;
  public $gid = -1;
  
  public function __construct($username, $uid, $gid) {
    $this->username = $username;
    $this->uid = $uid;
    $this->gid = $gid;
  }

}


?>