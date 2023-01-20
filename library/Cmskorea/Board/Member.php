<?php
/**
 * CMSKOREA BOARD
 *
 * @category Cmskorea
 * @package  Board
 */
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
    public function __construct($dbHost, $userName, $userPw, $dbName) {
        $this->_mysqli = mysqli_connect($dbHost, $userName, $userPw, $dbName);
        if (!$this->_mysqli) {
            die("DB 접속중 문제가 발생했습니다. : ".mysqli_connect_error());
        }
    }
    

    /**
     * 회원을 등록한다.
     * 동일한 아이디의 회원을 등록 할 수 없다.
     *
     * @throws Exception 동일한 아이디의 회원이 존재하는 경우
     *                   필수 항목을 입력하지 않았을 경우
     *                   입력 형식을 지키지 않았을 경우
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
        $manageArrays = array(
            'id'        => array(
                                "kor" => "아이디",
                                "reg" => "/^[A-Za-z0-9]+$/"),
            'pw'        => array(
                                "kor" => "비밀번호",
                                "reg" => "/(?=.*[~`!@#$%\^&*()-+=])[A-Za-z0-9~`!@#$%\^&*()-+=]+$/"),
            'name'      => array(
                                "kor" => "이름",
                                "reg" => "/[가-힣A-Za-z]+$/"),
            'telNumber' => array(
                                "kor" => "휴대전화",
                                "reg" => "/^(010|011|016|017|018|019)-[0-9]{3,4}-[0-9]{4}$/")
        );
        
        foreach ($manageArrays as $field => $value) {
            if (!$datas[$field]) {
                throw new Exception('필수 항목을 모두 입력해주세요.');
            }
            
            if ($value['reg']) {
                if (!preg_match($value['reg'], $datas[$field])) {
                    throw new Exception($value['kor'].' 입력 형식을 지켜주세요.');
                }
            }
        }
        
        $fId = mysqli_real_escape_string($this->_mysqli, $datas['id']);
        $fPw = mysqli_real_escape_string($this->_mysqli, $datas['pw']);
        $fName = mysqli_real_escape_string($this->_mysqli, $datas['name']);
        $fTelNumber = mysqli_real_escape_string($this->_mysqli, $datas['telNumber']);
        
        $sql1 = "SELECT COUNT(id) AS count FROM member WHERE id='{$fId}'";
        $res = mysqli_query($this->_mysqli, $sql1);
        $searchRow = mysqli_fetch_assoc($res);
        if ($searchRow['count'] > 0) {
            throw new Exception('이미 동일한 아이디가 존재합니다.');
        }
        
        $processedTel = str_replace('-', '', $fTelNumber);
        $sql2 = "INSERT INTO member(id, pw, name, telNumber, insertTime) VALUES ('{$fId}', MD5('{$fPw}'), '{$fName}', '{$processedTel}', now())";
        mysqli_query($this->_mysqli, $sql2);
        if (mysqli_errno($this->_mysqli)) {
            throw new Exception('회원 등록에 실패했습니다. 관리자에게 문의해주십시오.');
        }
        
        return $this;
    }

    /**
     * 아이디에 해당하는 회원정보를 리턴한다.
     *
     * @param string 회원아이디
     * @return array
     *         array(
     *            'pk'        => '회원고유번호',
     *            'id'        => '아이디',
     *            'name'      => '회원명',
     *            'telNumber' => '연락처'
     *        )
     */
    public function getMember($id) {
        $fId = mysqli_real_escape_string($this->_mysqli, $id);
        $sql = "SELECT pk, id, name, telNumber FROM member where id='{$fId}'";
        $res = mysqli_query($this->_mysqli, $sql);
        if (!$res) {
            return array();
        }
        
        $row = mysqli_fetch_assoc($res);
        if ($row == NULL) {
            return array();
        }
        
        return $row;
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
        
        $sql = "SELECT id, pw FROM member WHERE id='{$fId}' AND pw=MD5('{$fPw}')";
        $res = mysqli_query($this->_mysqli, $sql);
        $row = mysqli_fetch_assoc($res);
        if (!$row) {
            return "아이디 또는 비밀번호가 일치하지 않습니다.";
        }
        
        return '';
    }
}

