<?php
session_start();
/**
 * require bootstrap
 */
require_once __DIR__.'/bootstrap.php';
/**
 * @see Cmskorea_Board_Auth
 */
require_once '/Cmskorea/Board/Auth.php';
/**
 * @see Cmskorea_Board_member
 */
require_once '/Cmskorea/Board/Member.php';
/**
 * Cmskorea_Board_Auth 테스트를 위한 클래스
 * CmsKorea_Board_AuthTestClass
 */
class CmsKorea_Board_AuthTestClass extends Cmskorea_Board_Auth {
    public function __construct() {
        $this->_member = new Cmskorea_Board_MemberTestClass();
    }
}
/**
 * CmsKorea_Board_AuthTestClass에 test DB를 연결하기 위한 클래스
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
 * Cmskorea_Board_Auth test case.
 */
class Cmskorea_Board_AuthTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var CmsKorea_Board_AuthTestClass
     */
    private $auth;
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
        $this->auth = new CmsKorea_Board_AuthTestClass();
        $this->member = new Cmskorea_Board_MemberTestClass();
        $sql = "INSERT INTO member(id, pw, name, telNumber, insertTime) VALUES ('test', MD5('1111@'), '테스터', '01012341234', NOW())";
        $res = mysqli_query($this->member->getMysqli(), $sql);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $sql = "DELETE FROM member";
        $res = mysqli_query($this->member->getMysqli(), $sql);
        mysqli_close($this->member->getMysqli());
        $this->auth = null;

        parent::tearDown();
    }

    /**
     * Tests Cmskorea_Board_Auth->__construct()
     */
    public function test__construct()
    {
        $this->auth->__construct();
    }

    /**
     * Tests Cmskorea_Board_Auth->authenticate()
     */
    public function testAuthenticate()
    {
        $res = $this->auth->authenticate('test', "1111@");
        $this->assertEquals('', $res);
        $res = $this->auth->authenticate('test', "1111@!");
        $this->assertEquals("아이디 또는 비밀번호가 일치하지 않습니다.", $res);
    }

    /**
     * Tests Cmskorea_Board_Auth->getMember()
     */
    public function testGetMember()
    {
        $this->auth->authenticate('test', "1111@");
        $ans = array(
            'id'        => 'test',
            'name'      => '테스터',
            'telNumber' => '01012341234'
        );
        $res = $this->auth->getMember();
        $this->assertEquals($ans, $res);
    }

    /**
     * Tests Cmskorea_Board_Auth->isLogin()
     */
    public function testIsLogin()
    {
        $this->auth->authenticate('test', "1111@");
        $res = $this->auth->isLogin();
        $this->assertEquals(true, $res);
    }

    /**
     * Tests Cmskorea_Board_Auth->logout()
     */
    public function testLogout()
    {
        $this->auth->authenticate('test', "1111@");
        $res = $this->auth->logout();
        $this->assertEquals(true, $res);
    }
}

