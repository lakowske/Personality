<?php

require_once('site_config.php');
require_once('Database.php');

class SessionManager
{
  var $d;
  var $life_time;
  private $databaseSupplier;

  function SessionManager($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
    $this->d = $this->databaseSupplier->get();
    $this->life_time = 3600 * 2;

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
    
    error_log('session id: ' . $session_id);
    $newid = pg_escape_string($session_id);
    $sql = "SELECT data FROM session WHERE session_id = '$newid' AND expire_time > CURRENT_TIMESTAMP";
    $rs = $this->d->query($sql);
    error_log('matching sessions: ' . $this->d->num_rows());

    if ($this->d->num_rows() > 0) {
      $row = $this->d->fetch_row();
      $data = $row[0];
    } 

    return $data;
  }

  function write ($session_id, $data) {
    $time = time() + $this->life_time;
    $sqlDateTimeExpire = date('Y-m-d H:i:s', $time);    
    $now = time();
    $sqlDateTimeNow = date('Y-m-d H:i:s', $now);  
  
    $newid = pg_escape_string($session_id);
    $newdata = pg_escape_string($data);
    error_log('writing: ' . $data);
    error_log('to session_id: ' . $newid);
    error_log("newdata: $newdata");
    $existSql = "Select session_id FROM session WHERE session_id = '$newid'";
    $this->d->query($existSql);
    $sql = '';
    if ($this->d->num_rows() > 0) {
      $sql = "UPDATE session SET data = '$newdata', expires = $time, expire_time = '$sqlDateTimeExpire' WHERE session_id = '$newid'";
    } else {
      $sql = "INSERT INTO session VALUES('$newid', $time, '$sqlDateTimeNow', '$sqlDateTimeExpire', '$newdata')";
    }
    error_log($sql);
    $this->d->query("BEGIN");
    $this->d->query($sql);
    $this->d->query("COMMIT");
    return TRUE;
  }

  function destroy( $session_id ) {

    $newid = pg_escape_string($session_id);
    $sql = "DELETE FROM session WHERE session_id = '$newid'";

    $this->d->query("BEGIN");
    $this->d->query($sql);
    $this->d->query("COMMIT");
  }

  function gc() {
    $sql = "DELETE FROM session WHERE expire_time > CURRENT_TIMESTAMP;";

    $this->d->query("BEGIN");
    $this->d->query($sql);
    $this->d->query("COMMIT");
    
    return true;
  }
}
