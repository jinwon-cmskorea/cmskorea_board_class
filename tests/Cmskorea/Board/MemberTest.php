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
 * Cmskorea_Board_Member 테스트를 위한 클래스
 * Cmskorea_Board_MemberTestClass
 */
class Cmskorea_Board_MemberTestClass extends Cmskorea_Board_Member {
    /**
     * 테스트를 위한 생성자 변경
     */
    public function __construct() {
        $this->_mysqli = mysqli_connect(DBHOST, USERNAME, USERPW, 'cmskorea_board_test');
        if (!$this->_mysqli) {
            die("DB 접속중 문제가 발생했습니다. : ".mysqli_connect_error());
        }
    }
    
    /**
     * 테스트를 위해 mysqli 객체 접근
     * @return mysqli_connect
     */
    public function getMysqli() {
        return $this->_mysqli;
    }
}
/**
 * Cmskorea_Baord_Member test case.
 */
class Cmskorea_Baord_MemberTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Cmskorea_Board_MemberTestClass
     */
    private $member;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->member = new Cmskorea_Board_MemberTestClass();
        $sql = "INSERT INTO member(id, pw, name, telNumber, insertTime) VALUES ('test', MD5('1111'), '테스터', '01012341234', NOW())";
        $res = mysqli_query($this->member->getMysqli(), $sql);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $sql = "DELETE FROM member WHERE id='test'";
        $res = mysqli_query($this->member->getMysqli(), $sql);
        $this->member = null;

        parent::tearDown();
    }

    /**
     * Tests Exception Cmskorea_Baord_Member->registMember()
     */
    public function testRegistMemberException()
    {
//         $this->member->registMember(/* parameters */);
        $test1 = array(
            'id' => 'test',
            'pw' => '1234',
            'name' => '테스터',
            'telNumber' => '010-1234-1234'
        );
        
        try {
            $this->member->registMember($test1);
        } catch(Exception $e) {
            $this->assertEquals('이미 동일한 아이디가 존재합니다.',$e->getMessage());
            $this->assertTrue(true);
        }
        $this->assertFalse(false);
    }
    
    /**
     * Tests Cmskorea_Baord_Member->registMember()
     */
    public function testRegistMember()
    {
        $id = "notOverlapId";
        $test2 = array(
            'id' => $id,
            'pw' => '1234',
            'name' => '중복아님',
            'telNumber' => '010-4321-4321'
        );
        try {
            $result = $this->member->registMember($test2);
        } catch(Exception $e) {
            $this->assertFalse(false);//예외를 던지면 실패한 것
        }
        
        $expacted = $this->member->getMember($id);
        unset($test2['pw']);
        $test2['telNumber'] = str_replace('-', '', $test2['telNumber']);
        $this->assertEquals($expacted, $test2);
    }

    /**
     * Tests Cmskorea_Baord_Member->getMember()
     */
    public function testGetMember()
    {
//         $this->member->getMember(/* parameters */);
        $testSql = "SELECT id, name, telNumber FROM member where id='test'";
        $testRes = mysqli_query($this->member->getMysqli(), $testSql);
        $testArray = mysqli_fetch_assoc($testRes);
        
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
        $this->assertEquals("아이디 또는 비밀번호가 일치하지 않습니다.", $res);
        $res = $this->member->authenticate('test', '1234');
        $this->assertEquals("아이디 또는 비밀번호가 일치하지 않습니다.", $res);
    }
}

