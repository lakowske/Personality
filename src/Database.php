<?php

class Database
{
  var $link = '';
  var $resource = 0;  //the resource from the last query
  
  function Database() {
     $dbuser = $GLOBALS['dbuser'];
     $dbname = $GLOBALS['dbname'];
     $dbhost = $GLOBALS['dbhost'];
     $dbpass = $GLOBALS['dbpass'];
     $dbport = $GLOBALS['dbport'];

     error_log('connecting to db as' . $GLOBALS['dbuser']);
     $this->link = pg_connect("host=$dbhost password=$dbpass dbname=$dbname user=$dbuser");
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
}

function num_rows($r) {
  return pg_numrows($r);
}
