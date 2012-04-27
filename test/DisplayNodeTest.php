<?php
require_once '../src/DisplayNode.php';
require_once '../src/Request.php';
require_once '../src/Template.php';
require_once '../src/Supplier.php';

class DisplayNodeTest extends PHPUnit_Framework_TestCase
{
  private $A = array("A" => 'hi', "B" => 'bye');
  protected $displayNode;
  protected $mockTemplate;
  protected $templateSupplier;

  public function getPost($username, $password) {
    return array('username' => $username, 'password' => $password);
  }

  public function setUp() {
    $map = array(array('template output'));
    $this->mockTemplate = $this->getMock('Template', array('display'));
    $this->mockTemplate->expects($this->any())->method('display')
      ->will($this->returnValueMap($map));

    $this->templateSupplier = new Supplier($this->mockTemplate);
    $this->displayNode = new DisplayNode('/test$/', function ($request) {return $request->isGet();}, 'test.tpl', $this->templateSupplier);
  }

  public function testLogin() {
    $postVars = $this->getPost('a', 'b');
    $sessionVars = array("rider" => 'a ride along value');
    $serverVars = array('REQUEST_URI' => '/~testuser/test');

    $r = new Request($postVars, $sessionVars, $serverVars, 'GET');
    $this->assertTrue($this->displayNode->evaluate($r));
    $this->assertEquals('template output', $this->displayNode->run($r));

  }


}
?>