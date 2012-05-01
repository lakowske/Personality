<?php
require_once('SessionManager.php');
require_once('database.php');
if(!array_key_exists('no_session', $GLOBALS)) {
  error_log("starting session");

  $sess = new SessionManager($databaseSupplier);

  session_start();
  
  error_log("session started");  
}
?>