<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Auth
 */
require_once '/Cmskorea/Board/Auth.php';
require_once __DIR__ .'/../../../configs/dbconfigs.php';
/* class Cmskorea_Board_Authtest extends Cmskorea_Board_Auth {
    
} */
/**
 * Cmskorea_Board_Auth test case.
 */
class Cmskorea_Board_AuthTest extends PHPUnit_Framework_TestCase
{
    //const $host = global $host;
    /**
     *
     * @var Cmskorea_Board_Auth
     */
    private $auth;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->auth = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->auth = null;
        
        parent::tearDown();
    }

    /**
     * Tests Cmskorea_Board_Auth->__construct()
     */
    public function test__construct()
    {
        $this->assertInstanceOf('Cmskorea_Board_Auth', $this->auth);
    }

    /**
     * Tests Cmskorea_Board_Auth->authenticate()
     */
    public function testAuthenticate()
    {
        //$this->markTestIncomplete("authenticate test not implemented");

        //$this->auth->authenticate(/* parameters */);
        $inId = $this->auth->authenticate("authtest", "authpw");
        $outId = $this->auth->authenticate("testidnot", "testpwnot");
        //var_dump($this->auth->authenticate("testidnot", "testpwnot"));
        //$this->assertNotEmpty($this->auth->authenticate("testidnot", "testpwnot"));
        //$this->assertNull($inId);
        $this->assertNotNull($inId);
        
        //$this->assertNull($outId);
        //$this->assertNotNull($outId);
    }

    /**
     * Tests Cmskorea_Board_Auth->getMember()
     */
    public function testGetMember()
    {
        //$this->markTestIncomplete("getMember test not implemented");

        //var_dump($this->auth->getMember("testidnot", "testpwnot"));
        $this->auth->authenticate("authtest", "authpw");
        $this->assertEquals("authtest", $this->auth->getMember()['id']);
    }

    /**
     * Tests Cmskorea_Board_Auth->isLogin()
     */
    public function testIsLogin()
    {
        //$this->markTestIncomplete("isLogin test not implemented");

        //$this->assertTrue($this->auth->isLogin());
        $result = $this->auth->isLogin();
        $this->assertFalse($result);
        //$this->assertTrue($result);
    }

    /**
     * Tests Cmskorea_Board_Auth->logout()
     */
    public function testLogout()
    {
        //$this->markTestIncomplete("logout test not implemented");
        $result = $this->auth->logout();
        $this->assertTrue($result);
        //$this->assertFalse($this->auth->logout());
    }
}

