<?php
require_once('Database.php');

//global user definitions
$GLOBALS['admin'] = 1;
$GLOBALS['trade_pl'] = 2;
$GLOBALS['tax_man'] = 3;

class User
{
  var $username = 'guest';
  var $uid = -1;
  var $gid = -1;
  
  function User() {
  }

  function session_end() {
    echo "session_end() needs implementing";
  }

  function login($username, $password) {
    $d = new Database();
    error_log("about to query\n");
    //put the password in $r
    $r = $d->query("select password, uid, gid from puser where username = '$username'");
    //now do a md5 compare
    $row = pg_fetch_row($r);
    if ($row[0] == md5($username . $password)) {
      $this->username = $username;
      $this->uid = $row[1];
      $this->gid = $row[2];
      return TRUE;
    }
    return FALSE;
  }

  function logout() {
    session_unregister('user');
    unset($_SESSION['user']);
    unset($user);
  }

  function admin() {
    if ($this->gid == 1) {
      return 1;
    }
    return 0;
  }
}

function username_uid($username) {
  $d = new Database();
  $r = $d->query("select uid from puser where username = '$username'");
  $r = $d->fetch_row();
  return $r[0];
}

?>