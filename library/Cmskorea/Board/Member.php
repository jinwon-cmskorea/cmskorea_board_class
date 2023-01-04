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
        $fId = mysqli_real_escape_string($this->_mysqli, $datas['id']);
        $fPw = mysqli_real_escape_string($this->_mysqli, $datas['pw']);
        $fName = mysqli_real_escape_string($this->_mysqli, $datas['name']);
        $fTelNumber = mysqli_real_escape_string($this->_mysqli, $datas['telNumber']);
        
        $sql1 = "SELECT * FROM member WHERE id='{$fId}'";
        $res = mysqli_query($this->_mysqli, $sql1);
        $searchRow = mysqli_fetch_array($res);
        if ($searchRow) {
            throw new Exception('이미 동일한 아이디가 존재합니다.');
        }
        
        $md5Pw = md5($fPw);
        $processedTel = str_replace('-', '', $fTelNumber);
        $insertTime = date("Y-m-d H:i:s");
        $sql2 = "INSERT INTO member(id, pw, name, telNumber, insertTime) VALUES ('{$fId}', '{$md5Pw}', '{$fName}', '{$processedTel}', '{$insertTime}')";
        $res2 = mysqli_query($this->_mysqli, $sql2);
        
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
        if (!$res) {
            return array();
        }
        
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
        
        if (!isset($row['id']) || md5($fPw) != $row['pw']) {
            return "아이디 또는 비밀번호가 일치하지 않습니다.";
        }
        
        return '';
    }
}

