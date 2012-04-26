<?php
require_once '../src/Request.php';

class RequestTest extends PHPUnit_Framework_TestCase
{
  public function testRequestIsMethods() {
    $a = array();
    $b = $a;
    $c = $b;
    $r = new Request($a, $b, $c, 'POST');
    $this->assertTrue($r->isPost());
    $this->assertFalse($r->isGet());
  }
}