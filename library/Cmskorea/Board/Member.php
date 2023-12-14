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
     * 
     * @var mysqli 연결 정보
     *      false 연결 실패
     */
    protected $_db;
    
    public function __construct($host, $userid, $password, $database) {
        $this->_db = mysqli_connect($host, $userid, $password, $database);
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
            //데이터 유효성 검사
            if (!((preg_match("/[0-9]/", $datas['id'])) || (preg_match("/[a-z]/i", $datas['id'])))) {
                throw new Exception("데이터 전달에 실패했습니다. 아이디를 영문 또는 숫자가 포함되도록 다시 작성해주세요.");
            }
            if (!(preg_match("/[~!@#$%^&*()_+|<>?:{}]/", $datas['pw']))) {
                throw new Exception("데이터 전달에 실패했습니다. 비밀번호는 특수문자 1개 필수입니다. 다시 작성해주세요.");
            }
            if (preg_match("/[~!@#$%^&*()_+|<>?:{}]/", $datas['name'])) {
                throw new Exception("데이터 전달에 실패했습니다. 이름을 한글 또는 영문만 있도록 다시 작성해주세요.");
            }
            if (!(preg_match("/^(?:(010-\d{4})|(01[1|6|7|8|9]-\d{3,4}))-(\d{4})$/", $datas['telNumber']))) {
                throw new Exception("데이터 전달에 실패했습니다. 휴대전화번호 형식을 일치하도록 다시 작성해주세요.");
            }
            $query = "INSERT INTO auth_identity (id, pw, name, insertTime) VALUES('" . $datas['id'] . "','". md5($datas['pw']) . "','" . $datas['name']. "', now())";
            
            $rs = mysqli_query($this->_db,$query);
            //
            if ($rs) {
                $query = "INSERT INTO member (id, name, telNumber, position, insertTime, updateTime)
                        VALUES ('" . $datas['id'] . "' ,'" . $datas['name'] . "' ,'" . str_replace('-', '', $datas['telNumber']) ."' , 5, now(), now())";
                
                $rs = mysqli_query($this->_db,$query);
                
                if (!$rs) {
                    throw new Exception('회원가입에 실패했습니다! 오류 확인 : ' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
                }
            } else {
                throw new Exception('회원가입에 실패했습니다! 오류 확인 : ' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
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
     *            'pk'        => '작성자고유키',
     *            'id'        => '아이디',
     *            'name'      => '회원명',
     *            'telNumber' => '연락처',
     *        )
     */
    public function getMember($id) {
        $query = "SELECT pk, id, name, telNumber FROM member WHERE id='" . $id . "';";
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
        return $result->num_rows > 0 ? '' : "아이디 또는 비밀번호가 일치하지 않습니다.";
    }
}

