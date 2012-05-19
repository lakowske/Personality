<?php
require_once '../src/PredicateUtil.php';

class PredicateUtilTest extends PHPUnit_Framework_TestCase
{
  protected $p1;
  protected $p2;
  protected $p3;


  public function setUp() {
    $this->p1 = function ($r) {return true;};
    $this->p2 = function ($r) {return true;};
    $this->p3 = function ($r) {return false;};
  }

  public function testAnd() {
    $predicate = a($this->p1, $this->p2);
    $predicate2 = a($this->p1, $this->p2, $this->p3);

    $this->assertTrue($predicate('hi'));
    $this->assertFalse($predicate2('hi'));

  }

  public function testOr() {
    $predicate = o($this->p1, $this->p3);
    $this->assertTrue($predicate('hi'));
  }

}

?>