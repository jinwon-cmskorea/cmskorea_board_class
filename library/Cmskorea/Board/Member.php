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
    protected $_db;
    
    public function __construct($host, $userid, $password, $database) {
        $this->_db = mysqli_connect($host, $userid, $password, $database);
        if ($this->_db) {
            return $this->_db;
        } else {
            return mysqli_error($this->_db);
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
     * @return Cmskorea_Board_Member
     */
    public function registMember(array $datas) {

        // 동일한 아이디의 회원 존재여부 체크
        if ($this->getMember($datas['id'])) {
            throw new Exception('중복된 아이디가 존재합니다!');
        } else {
            $query = "INSERT INTO member (id, name, telNumber, position, insertTime, updateTime) VALUES ('" . $datas['id'] . "' ,'" . $datas['name'] . "' ,'" . $datas['telNumber'] ."' , 5, now(), now())";
            $rs = mysqli_query($this->_db,$query);
            //
            if ($rs) {
                $query = "INSERT INTO auth_identity (id, pw, name, insertTime) VALUES('" . $datas['id'] . "','". md5($datas['pw']) . "','" . $datas['name']. "', now())";
                $rs = mysqli_query($this->_db,$query);
                
                if (!$rs) {
                    throw new Exception('회원가입에 실패했습니다!' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
                }
            } else {
                throw new Exception('회원가입에 실패했습니다!' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
            }
            if (!($this->getMember($datas['id']))) {
                throw new Exception('회원가입에 실패했습니다!');
            }
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
        $query = "SELECT id, name, telNumber FROM member WHERE id='" . $id . "';";
        $result = mysqli_query($this->_db, $query);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            return null;
        }
    }

    /**
     * 로그인 인증
     *
     * @param string 아이디
     * @param string 비밀번호
     * @return string 로그인 성공 시 빈값|로그인 불능 시 불능메시지
     */
    public function authenticate($id, $pw) {
        $query = "SELECT id FROM auth_identity WHERE id='" . $id . "' AND pw='" . md5($pw) . "';";
        $result = mysqli_query($this->_db, $query);
        return $result->num_rows > 0 ? null : "아이디 또는 비밀번호가 일치하지 않습니다.";
    }
}

