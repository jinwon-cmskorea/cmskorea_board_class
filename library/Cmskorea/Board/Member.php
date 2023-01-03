<?php
/**
 * CMSKOREA BOARD
 *
 * @category Cmskorea
 * @package  Board
 */
/**
 * @see dbCon.php
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/wwwroot/cmskorea_board_class/configs/dbConfig.php';
/**
 * 씨엠에스코리아 게시판 회원 클래스
 *
 * @category Cmskorea
 * @package  Board
 */
class Cmskorea_Board_Member {
    /**
     * DB연결 변수
     *
     * @var mysqli_connect 로 부터 리턴 받음
     */
    protected $_mysqli;
    
    /**
     * 생성자
     * @brief mysqli 객체를 생성해서 멤버변수에 넣어줌
     *
     * @return void
     */
    public function __construct() {
        $this->_mysqli = mysqli_connect(DBHOST, USERNAME, USERPW, DBNAME);
        if (!$this->_mysqli) {
            die("DB 접속중 문제가 발생했습니다. : ".mysqli_connect_error());
        }
    }
    

    /**
     * 회원을 등록한다.
     * 동일한 아이디의 회원을 등록 할 수 없다.
     *
     * @throws Exception 동일한 아이디의 회원이 존재하는 경우
     * @param array 회원가입정보
     *        array(
     *            'id'        => '아이디',
     *            'pw'        => '비밀번호',
     *            'name'      => '회원명',
     *            'telNumber' => '연락처',
     *        )
     * @return Cmskorea_Baord_Member
     */
    public function registMember(array $datas) {
        try {
            // 동일한 아이디의 회원의 존재여부 체크
        } catch (Exception $e) {
            throw new Exception('Member with the same ID exists.');
        }
        return $this;
    }

    /**
     * 아이디에 해당하는 회원정보를 리턴한다.
     *
     * @param string 회원아이디
     * @return array
     *         array(
     *            'id'        => '아이디',
     *            'name'      => '회원명',
     *            'telNumber' => '연락처',
     *        )
     */
    public function getMember($id) {
        $fId = mysqli_real_escape_string($this->_mysqli, $id);
        $sql = "SELECT * FROM member where id='{$fId}'";
        $res = mysqli_query($this->_mysqli, $sql);
        $row = mysqli_fetch_array($res);
        
        return array(
            'id'        => $row['id'],
            'name'      => $row['name'],
            'telNumber' => $row['telNumber']
        );
    }

    /**
     * 로그인 인증
     *
     * @param string 아이디
     * @param string 비밀번호
     * @return string 로그인 성공 시 빈값|로그인 불능 시 불능메시지
     */
    public function authenticate($id, $pw) {
        if (!$id) {
            return "아이디를 입력해주세요.";
        } else if (!$pw) {
            return "비밀번호를 입력해주세요.";
        }
        
        $fId = mysqli_real_escape_string($this->_mysqli, $id);
        $fPw = mysqli_real_escape_string($this->_mysqli, $pw);
        
        $sql = "SELECT * FROM member WHERE id='{$fId}'";
        $res = mysqli_query($this->_mysqli ,$sql);
        $row = mysqli_fetch_array($res);
        
        if (!isset($row['id'])) {
            return "존재하지 않는 아이디입니다.";
        } else if (md5($fPw) != $row['pw']) {
            return "비밀번호가 일치하지않습니다.";
        }
        
        return '';
    }
}

