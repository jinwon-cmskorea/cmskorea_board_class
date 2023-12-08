<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Auth
 */
require_once '/Cmskorea/Board/Auth.php';
require_once __DIR__ .'/../testconfigs/testdbconfigs.php';
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
        
        $this->auth = new Cmskorea_Board_Auth(TESTHOST, TESTUSERID, TESTPASSWORD, TESTDATABASE);
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
    public function testConstruct()
    {
        $this->assertInstanceOf('Cmskorea_Board_Auth', $this->auth);
    }

    /**
     * Tests Cmskorea_Board_Auth->authenticate()
     */
    public function testAuthenticate()
    {
        //$this->markTestIncomplete("authenticate test not implemented");

        $okId = $this->auth->authenticate("authtest", "authpw");
        $noId = $this->auth->authenticate("testidnot", "testpwnot");
        //var_dump($this->auth->authenticate("testidnot", "testpwnot"));
        $this->assertEmpty($okId);
        $this->assertNull($okId);
        
        $this->assertNotEmpty($noId);
        $this->assertNotNull($noId);
        $this->assertEquals("아이디 또는 비밀번호가 일치하지 않습니다.", $noId);
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
        $this->assertNotEquals("authNotest", $this->auth->getMember()['id']);
    }

    /**
     * Tests Cmskorea_Board_Auth->isLogin()
     */
    public function testIsLogin()
    {
        //$this->markTestIncomplete("isLogin test not implemented");
        $this->auth->authenticate("authtest", "authpw");
        $okresult = $this->auth->isLogin();
        $this->assertTrue($okresult);
        
        $this->auth->authenticate("testidnot", "testpwnot");
        $noresult = $this->auth->isLogin();
        $this->assertFalse($noresult);
    }

    /**
     * Tests Cmskorea_Board_Auth->logout()
     */
    public function testLogout()
    {
        //$this->markTestIncomplete("logout test not implemented");
        
        $result = $this->auth->logout();
        $this->assertTrue($result);
    }
}

