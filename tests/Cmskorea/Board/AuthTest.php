<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Auth
 */
require_once '/Cmskorea/Board/Auth.php';
/**
 * Cmskorea_Board_Auth test case.
 */
class Cmskorea_Board_AuthTest extends PHPUnit_Framework_TestCase
{

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

        $this->auth = new Cmskorea_Board_Auth(/* parameters */);
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
        $this->markTestIncomplete("__construct test not implemented");

        $this->auth->__construct(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Auth->authenticate()
     */
    public function testAuthenticate()
    {
        $this->markTestIncomplete("authenticate test not implemented");

        $this->auth->authenticate(/* parameters */);
        
    }

    /**
     * Tests Cmskorea_Board_Auth->getMember()
     */
    public function testGetMember()
    {
        $this->markTestIncomplete("getMember test not implemented");

        $this->auth->getMember(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Auth->isLogin()
     */
    public function testIsLogin()
    {
        $this->markTestIncomplete("isLogin test not implemented");

        $this->auth->isLogin(/* parameters */);
    }

    /**
     * Tests Cmskorea_Board_Auth->logout()
     */
    public function testLogout()
    {
        $this->markTestIncomplete("logout test not implemented");

        $this->auth->logout(/* parameters */);
    }
}

