<?php
require_once('User.php');

/**
 * UserSupplier provides a new user object.
 */
class UserSupplier
{
  public function get() {
    return new User();
  }
}

?>