<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Member
 */
require_once '/Cmskorea/Board/Member.php';
require_once __DIR__ .'/../testconfigs/dbconfigs.php';
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
        $this->member = new Cmskorea_Board_Member(TESTHOST, TESTUSERID, TESTPASSWORD, TESTDATABASE);
        //mysqli_query(mysqli_connect(TESTHOST, TESTUSERID, TESTPASSWORD, TESTDATABASE), "DELETE FROM member");
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
        
        $testUser = array(
                'id'        => 'authtest',
                'pw'        => 'authpw',
                'name'      => 'authname이름',
                'telNumber' => 'authtelNumber12345'
        );
        if (empty($this->member->getMember($testUser['id']))) {
            $result = $this->member->registMember($testUser);
            $this->assertInstanceOf('Cmskorea_Board_Member', $result);
        } else {
            try {
                $result = $this->member->registMember($testUser);
            } catch (Exception $e) {
                $this->assertEquals('중복된 아이디가 존재합니다!', $e->getMessage());
            }
        }
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
        
        $this->assertNotEquals($noID, $noresult['id']);
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
        $this->assertNotNull($noresult);
    }
}

