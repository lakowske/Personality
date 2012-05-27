<?php

class GroupManager
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }

  function addGroup($groupName, $description) {
    $d = $this->databaseSupplier->get();
    
    $dbGroupName = $d->escape_string($groupName);
    $dbDescription = $d->escape_string($description);

    $d->query("BEGIN");

    $r = $d->query("insert into pgroup (name, description) "
		   . "values('{$dbGroupName}', '{$dbDescription}')");

    $d->query("COMMIT");
  }

  function add_to_group($username, $groupname) {
    $d = $this->databaseSupplier->get();

    $d->query("BEGIN");
    
    $r = $d->query("insert into groupuser (gid, uid) "
                   . "values('{$gid}', '{$uid}')");

    $d->query("COMMIT");
    
    return 1;
  }

  function get_groups($uid) {
    $d = $this->databaseSupplier->get();

    $r = $d->query("select gid from groupuser where uid = '{$uid}'");

    return $d->fetch_all();
  }    

  
  function delete_user_from_group($uid, $gid) {
    $d = $this->databaseSupplier->get();

    $d->query("delete from groupuser where uid = '{$uid}' and gid = '{$gid}'");

    return 1;
  }
}

?>