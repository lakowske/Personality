<?php

class Database
{
  var $link = '';
  var $resource = 0;  //the resource from the last query
  
  function Database($user, $pass, $database, $host, $port) {
     error_log("connecting to $host / $database as $user");
     $this->link = pg_connect("host=$host password=$pass dbname=$database user=$user");
  }
  
  public static function fromGlobals() {
     $dbuser = $GLOBALS['dbuser'];
     $dbname = $GLOBALS['dbname'];
     $dbhost = $GLOBALS['dbhost'];
     $dbpass = $GLOBALS['dbpass'];
     $dbport = $GLOBALS['dbport'];
     
     return new Database($dbuser, $dbpass, $dbname, $dbhost, $dbport);
  }

  function query($q) {
    $this->resource = pg_exec($this->link, $q);
    if(!$this->resource) {
      error_log("couldnt do it captn");
      echo "<BR>sent: $q<BR><BR>";
      echo pg_errormessage($this->link);
    }
    return $this->resource;
  }

  function fetch_row() {
    if ($this->resource == 0) {
      return FALSE;
    }
    return pg_fetch_row($this->resource);
  }

  function fetch_all() {
    if ($this->resource == 0) {
      return FALSE;
    }
    return pg_fetch_all($this->resource);
  }
    
  function num_rows() {
    if ($this->resource == 0) {
      return -1;
    }
    return pg_numrows($this->resource);
  }

  function escape_string($string) {
    return pg_escape_string($string);
  }

}

function num_rows($r) {
  return pg_numrows($r);
}
