<?php

/**
 * Supplier supplies something when get is called.  In the
 * basic implementation a private variable is returned.
 */
class Supplier
{
  private $value;

  public function __construct($value) {
    $this->value = $value;
  }

  public function get() {
    return $this->value;
  }

}
?>