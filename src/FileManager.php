<?php

/**
 * The File DAO (Data Access Interface) interface.
 */
class FileManager
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }

  function get_file($filename) {
    $d = $this->databaseSupplier->get();
    $r = $d->query("select uid, gid, fpath, filename, create_date, group_permissions from file where filename = '{$filename}'");
    $row = pg_fetch_row($r);
    return $row;
  }

  function get_user_files($uid) {
    $d = $this->databaseSupplier->get();
    $r = $d->query("select uid, gid, fpath, filename, origname, create_date, group_permissions from file where uid = '{$uid}'");
    $arr = $d->fetch_all();
    return $arr;
  }

  function add_file($origname, $filename, $fpath, $uid, $gid, $perm_id) {
    $d = $this->databaseSupplier->get();

    $d->query("BEGIN");

    $r = $d->query("insert into file (uid, gid, fpath, filename, origname, create_date, group_permissions) "
		   . "values('{$uid}', '{$gid}', '{$fpath}', '{$filename}', '{$origname}', current_timestamp, '{$perm_id}')");

    if ($r == FALSE) {
      return FALSE;
    }

    $d->query("COMMIT");

    return TRUE;
  }

  function getReadOnlyPermissions() {
    $d = $this->databaseSupplier->get();

    $r = $d->query("select permid from permission where pread = true and pwrite = false");

    $r = pg_fetch_row($r);

    $perm_id = $r[0];

    return $perm_id;
  }
}

?>