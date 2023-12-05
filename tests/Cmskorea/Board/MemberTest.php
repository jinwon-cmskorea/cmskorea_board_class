<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Baord_Member
 */
require_once '/Cmskorea/Board/Member.php';
require_once __DIR__ .'/../../../configs/dbconfigs.php';
/**
 * Cmskorea_Baord_Member test case.
 */
class Cmskorea_Baord_MemberTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Cmskorea_Baord_Member
     */
    private $member;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->member = new Cmskorea_Baord_Member(HOST, USERID, PASSWORD, DATABASE);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->member = null;

        parent::tearDown();
    }

    /**
     * Tests Cmskorea_Baord_Member->registMember()
     */
    public function testRegistMember()
    {
        //$this->markTestIncomplete("registMember test not implemented");
        $testuser = array('id' => 'authtest', 'pw' => 'authpw', 'name' => 'authname이름', 'telNumber' => 'authtelNumber12345');
        //$this->member->registMember($testuser);
    }

    /**
     * Tests Cmskorea_Baord_Member->getMember()
     */
    public function testGetMember()
    {
        //$this->markTestIncomplete("getMember test not implemented");
        $testID = 'authtest';
        $this->assertArrayHasKey('name', $this->member->getMember($testID));
        $this->assertNotEmpty('Cmskorea_Board_Member', $this->member->getMember($testID));
    }

    /**
     * Tests Cmskorea_Baord_Member->authenticate()
     */
    public function testAuthenticate()
    {
        //$this->markTestIncomplete("authenticate test not implemented");

        //$this->member->authenticate(/* parameters */);
        $testID = 'authtest';
        $testPW = 'authpw';
        //echo $this->member->authenticate($testID, $testPW) . "확인";
        $this->assertNull($this->member->authenticate($testID, $testPW));
    }
}

