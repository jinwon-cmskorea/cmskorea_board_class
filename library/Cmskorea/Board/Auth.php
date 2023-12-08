<?php
/**
 * CMSKOREA BOARD
 *
 * @category Cmskorea
 * @package  Board
 */
/**
 * @see Cmskorea_Board_Member
 */
require_once 'member.php';
/**
 * 씨엠에스코리아 사용자 인증 클래스
 *
 * @category Cmskorea
 * @package  Board
 */
class Cmskorea_Board_Auth {
    /**
     * 씨엠에스코리아 사용자 클래스
     *
     * @var Cmskorea_Board_Member
     */
    protected $_member;
    protected $_db;
    /**
     * Session 네임스페이스
     * @var string
     */
    const SESSION_NAMESPACE = 'cmskoreaMember';

    /**
     * 생성자
     *
     * @return void
     */
    public function __construct($host, $userid, $password, $database) {
        $this->_member = new Cmskorea_Board_Member($host, $userid, $password, $database);
        $this->_db = mysqli_connect($host, $userid, $password, $database);
    }

    /**
     * 로그인 인증
     *  - 로그인에 성공한 경우 세션에 로그인에 성공한 회원정보를 보관한다.
     *
     * @param string 아이디
     * @param string 비밀번호
     * @return string 로그인 성공 시 빈값|로그인 불능 시 불능메시지
     */
    public function authenticate($id, $pw) {
        // Cmskorea_Board_Member 로 위임
        $authResult = $this->_member->authenticate($id, $pw);
        // 로그인 성공 시 세션에 회원정보를 저장한다.
        $memberInfo = $this->_member->getMember($id);
        $_SESSION[self::SESSION_NAMESPACE] = $memberInfo;
        
        return $authResult;
    }

    /**
     * 세션에 설정된 회원정보를 리턴한다.
     *
     * @throws Exception 설정된 회원정보가 없는 경우 예외처리
     * @return array
     */
    public function getMember() {
        if (!isset($_SESSION[self::SESSION_NAMESPACE]) || empty($_SESSION[self::SESSION_NAMESPACE])) {
            throw new Exception('회원 정보가 설정되지 않았습니다.');
        }

        return $_SESSION[self::SESSION_NAMESPACE];
    }

    /**
     * 로그인 여부를 확인 한다.
     *
     * @return boolean
     */
    public function isLogin() {
        try {
            $memberInfo = $this->getMember();
        } catch (Exception $e) {
            return false;
        }

        return !empty($memberInfo) ? true : false;
    }

    /**
     * 로그아웃
     *  - 로그인 시 생성된 세션을 파괴한다.
     *
     * @return boolean
     */
    public function logout() {
        unset($_SESSION[self::SESSION_NAMESPACE]);
        
        return true;
    }
}

