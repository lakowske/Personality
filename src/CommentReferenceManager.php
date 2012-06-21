<?php

class CommentReferenceManager
{
  private $databaseSupplier;
  
  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }

  public function removeCommentReferences($cid) {
    $d = $this->databaseSupplier->get();

    $r = $d->query("BEGIN");

    $d->query("delete from commentref where cid = '$cid'");

    $r = $d->query("COMMIT");
  }
}

?>