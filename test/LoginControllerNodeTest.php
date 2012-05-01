<?php
require_once '../src/LoginControllerNode.php';
require_once '../src/User.php';
require_once '../src/Supplier.php';
require_once '../src/Request.php';

class LoginControllerNodeTest extends PHPUnit_Framework_TestCase
{
  private $A = array("A" => 'hi', "B" => 'bye');
  protected $loginControllerNode;
  protected $username;
  protected $username2;
  protected $password;

  public function getPost($username, $password) {
    return array('username' => $username, 'password' => $password);
  }

  public function setUp() {
    $user = $this->getMockBuilder('User')->disableOriginalConstructor()->getMock();

    $this->username = 'testuser';
    $this->username2 = 'jackson';
    $this->password = 'password';

    $map = array(array($this->username, $this->password, True),
		 array($this->username2, $this->password, False)
		 );

    $user->expects($this->any())->method('login')
      ->will($this->returnValueMap($map));
    
    $userSupplier = new Supplier($user);
    $user->login($this->username, $this->password);
    $u = $userSupplier->get();
    $this->loginControllerNode = new LoginControllerNode($userSupplier);
  }
  public function testLogin() {
    $postVars = $this->getPost($this->username, $this->password);
    $sessionVars = array("rider" => 'a ride along value');
    $serverVars = array();

    $r = new Request($postVars, $sessionVars, $serverVars, 'POST');
    $this->loginControllerNode->run($r);
    $sessionVars = $r->getSessionVars();
    var_dump($sessionVars);


    $postVars = $this->getPost("jackson", $this->password);
    $r = new Request($postVars, $sessionVars, $serverVars, 'POST');
    $this->loginControllerNode->run($r);
    $sessionVars = $r->getSessionVars();
    var_dump($sessionVars);

    $postVars = $this->getPost("johnny", $this->password);
    $r = new Request($postVars, $sessionVars, $serverVars, 'POST');
    $this->loginControllerNode->run($r);
    $sessionVars = $r->getSessionVars();
    var_dump($sessionVars);

  }

  public function testPredicate() {
    $postVars = $this->getPost($this->username, $this->password);
    $sessionVars = array("rider" => 'a ride along value');
    $serverVars = array('REQUEST_URI' => '/here/i/am');

    $r = new Request($postVars, $sessionVars, $serverVars, 'POST');
    $this->assertFalse($this->loginControllerNode->evaluate($r));

    $postVars = $this->getPost($this->username, $this->password);
    $sessionVars = array("rider" => 'a ride along value');
    $serverVars = array('REQUEST_URI' => '/here/i/am/login');

    $r = new Request($postVars, $sessionVars, $serverVars, 'POST');
    $this->assertTrue($this->loginControllerNode->evaluate($r));

    $r = new Request($postVars, $sessionVars, $serverVars, 'GET');
    $this->assertFalse($this->loginControllerNode->evaluate($r));

  }

}

?>