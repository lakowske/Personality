<?php

class UserManager
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }
  
  function login($username, $password) {
    $d = $this->databaseSupplier->get();
    error_log("about to query\n");
    //put the password in $r
    $r = $d->query("select password, uid, gid from puser where username = '$username'");
    //now do a md5 compare
    $row = pg_fetch_row($r);
    if ($row[0] == md5($username . $password)) {
      return new User($username, $row[1], $row[2]);
    }
    return FALSE;
  }
  
  function logout() {
    session_unregister('user');
    unset($_SESSION['user']);
  }

  function admin() {
    if ($this->gid == 1) {
      return 1;
    }
    return 0;
  }

  function username_uid($username) {
    $d = $this->databaseSupplier->get();
    $r = $d->query("select uid from puser where username = '$username'");
    $r = $d->fetch_row();
    return $r[0];
  }


  function add_user($username, $password, $description, $email) {
    $d = $this->databaseSupplier->get();

    //put the password in $r
    $key = md5($username . $password);

    $d->query("BEGIN");
    $r = $d->query("insert into pgroup (name, description) "
                   . "values('$username', '$description')");

    $r = $d->query("select gid from pgroup where name = '$username'");

    $r = pg_fetch_row($r);

    $g_id = $r[0];
    
    $r = $d->query("insert into puser (gid, username, password, inception, email)"
                   . "values('$g_id', '$username', '$key', current_timestamp, '$email')");
    if($r == FALSE) {
      return FALSE;
    }
    $d->query("COMMIT");

    return TRUE;
  }

  function add_to_group($username, $groupname) {
    return 1;
  }
  
  function delete_user($username) {
    $d = $this->databaseSupplier->get();

    $d->query("delete from users where username = '$username'");
  }
}

?>