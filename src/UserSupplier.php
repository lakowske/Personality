<?php
require_once('User.php');

/**
 * UserSupplier provides a new user object.
 */
class UserSupplier
{
  private $databaseSupplier;

  public function __construct($databaseSupplier) {
    $this->databaseSupplier = $databaseSupplier;
  }

  public function get() {
    return new User($this->databaseSupplier);
  }
}

?>