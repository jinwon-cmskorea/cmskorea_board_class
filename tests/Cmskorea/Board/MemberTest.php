<?php
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Baord_Member
 */
require_once '/Cmskorea/Board/Member.php';
/**
 * Cmskorea_Baord_Member test case.
 */
class Cmskorea_Baord_MemberTest extends PHPUnit_Framework_TestCase
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
        $this->member = new Cmskorea_Board_Member();
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
//         $this->member->registMember(/* parameters */);
    }

    /**
     * Tests Cmskorea_Baord_Member->getMember()
     */
    public function testGetMember()
    {
//         $this->member->getMember(/* parameters */);
        $testArray = array (
            'id'        => 'test',
            'name'      => '테스트',
            'telNumber' => '01012341234'
        );
        $res = $this->member->getMember('test');
        $this->assertEquals($testArray, $res);
        $res = $this->member->getMember('notuser');
        $this->assertNotEquals($testArray, $res);
    }

    /**
     * Tests Cmskorea_Baord_Member->authenticate()
     */
    public function testAuthenticate()
    {
        $res = $this->member->authenticate('', '1111');
        $this->assertEquals("아이디를 입력해주세요.", $res);
        $res = $this->member->authenticate('test', '');
        $this->assertEquals("비밀번호를 입력해주세요.", $res);
        $res = $this->member->authenticate('test', '1111');
        $this->assertEquals('', $res);
        $res = $this->member->authenticate('notuser', '1111');
        $this->assertEquals("존재하지 않는 아이디입니다.", $res);
        $res = $this->member->authenticate('test', '1234');
        $this->assertEquals("비밀번호가 일치하지않습니다.", $res);
    }
}

