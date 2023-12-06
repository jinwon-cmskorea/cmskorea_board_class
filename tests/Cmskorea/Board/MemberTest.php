<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Member
 */
require_once '/Cmskorea/Board/Member.php';
require_once __DIR__ .'/../../../configs/dbconfigs.php';
/**
 * Cmskorea_Board_Member test case.
 */
class Cmskorea_Board_MemberTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Cmskorea_Board_Member
     */
    private $member;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->member = new Cmskorea_Board_Member(HOST, USERID, PASSWORD, TESTDATABASE);
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
     * Tests Cmskorea_Board_Member->registMember()
     */
    public function testRegistMember()
    {
        //$this->markTestIncomplete("registMember test not implemented");
        
        $testuser = array(
                'id'        => 'authtest',
                'pw'        => 'authpw',
                'name'      => 'authname이름',
                'telNumber' => 'authtelNumber12345'
        );
        $okresult = $this->member->registMember($testuser);
        $this->assertInstanceOf('Cmskorea_Board_Member', $okresult);
        
        $testNouser = array('id' => 'authNotest', 'pw' => 'authNopw');
        $noresult = $this->member->registMember($testNouser);
        $this->assertInstanceOf('Cmskorea_Board_Member', $noresult);
        
        $this->assertEquals($okresult, $noresult);
    }

    /**
     * Tests Cmskorea_Board_Member->getMember()
     */
    public function testGetMember()
    {
        //$this->markTestIncomplete("getMember test not implemented");
        $okID = 'authtest';
        $noID = 'authNoIdtest';
        $okresult = $this->member->getMember($okID);
        $noresult = $this->member->getMember($noID);
        
        $this->assertArrayHasKey('name', $okresult);
        $this->assertEquals($okID, $okresult['id']);
        $this->assertNotEmpty('Cmskorea_Board_Member', $okresult);
        
        $this->assertEquals($noID, $noresult['id']);
        $this->assertNotEmpty('Cmskorea_Board_Member', $noresult);
    }

    /**
     * Tests Cmskorea_Board_Member->authenticate()
     */
    public function testAuthenticate()
    {
        //$this->markTestIncomplete("authenticate test not implemented");

        $okID = 'authtest';
        $okPW = 'authpw';
        
        $noID = 'authNoIdtest';
        $noPW = 'authNoIdpw';
        
        $okresult = $this->member->authenticate($okID, $okPW);
        $noresult = $this->member->authenticate($noID, $noPW);
        
        $this->assertNull($okresult);
        $this->assertNull($noresult);
    }
}

