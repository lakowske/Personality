<?php

require_once('site_config.php');
require_once('Database.php');

class SessionManager
{
  var $d;
  var $life_time;

  function SessionManager() {
    $this->d = new Database();
    $this->life_time = get_cfg_var("session.gc_maxlifetime");

    session_set_save_handler(
			     array( &$this, "open" ),
			     array( &$this, "close" ),
			     array( &$this, "read" ),
			     array( &$this, "write" ),
			     array( &$this, "destroy" ),
			     array( &$this, "gc")
			     );

  }


  function open( $save_path, $session_name) {
    return true;
  }

  function close() {
    return true;
  }

  function read( $session_id ) {
    $data = '';

    $time = time();

    $newid = pg_escape_string($session_id);
    $sql = "SELECT data FROM session WHERE session_id = '$newid' AND expires > $time";
    $rs = $this->d->query($sql);
    
    if ($this->d->num_rows() > 0) {
      $row = $this->d->fetch_row();
      $data = $row[0];
    }

    return $data;
  }

  function write ($session_id, $data) {
    $time = time() + $this->life_time;
    
    error_log($data);
    $newid = pg_escape_string($session_id);
    $newdata = pg_escape_string($data);
    error_log($newdata);
    $existSql = "Select session_id FROM session WHERE session_id = '$newid'";
    $this->d->query($existSql);
    $sql = '';
    if ($this->d->num_rows() > 0) {
      $sql = "UPDATE session SET data = '$newdata', expires = $time WHERE session_id = '$newid'";
    } else {
      $sql = "INSERT INTO session VALUES('$newid', $time, '$newdata')";
    }

    $this->d->query($sql);
    
    return TRUE;
  }

  function destroy( $session_id ) {

    $newid = pg_escape_string($session_id);
    $sql = "DELETE FROM session WHERE session_id = '$newid'";
    $this->d->query($sql);

  }

  function gc() {
    $sql = "DELETE FROM session WHERE expires < UNIX_TIMESTAMP();";
    $this->d->query($sql);
    
    return true;
  }
}