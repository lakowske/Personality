<?php
require_once('site_config.php');
require_once('Database.php');

class UserManager
{

  function UserManger() {
    
  }

  function add_user($username, $password, $description, $email) {
    $d = new Database();
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
    $d = new Database();

    $d->query("delete from users where username = '$username'");
  }
}

?>