<?php
require_once '../src/RegPred.php';
require_once '../src/Request.php';

class RegPredTest extends PHPUnit_Framework_TestCase
{
  protected $request;
  protected $request2;

  protected $reg;

  public function setUp() {
    $this->reg = '/^\/testing\/(.+)/';
    $a = array();
    $b = array();
    $c = array();
    $d = array('PATH_INFO' => '/testing/10');
    $this->request = new Request($a, $b, $d, $c);
    $e = array('PATH_INFO' => '/tester/10');
    $this->request2 = new Request($a, $b, $e, $c);
  }

  public function testRegPred() {
    $regPred = new RegPred($this->reg);
    $b = $regPred->evaluate($this->request);
    $this->assertTrue($b);
    $matches = $regPred->getMatches();
    $this->assertTrue(strcmp($matches[1][0], '10') == 0);

    $b = $regPred->evaluate($this->request2);
    $this->assertFalse($b);
  }

}
?>