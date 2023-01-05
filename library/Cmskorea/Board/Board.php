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
 * 씨엠에스코리아 게시판 클래스
 *
 * @category Cmskorea
 * @package  Board
 */
class Cmskorea_Board_Board {
    /**
     * DB연결 변수
     *
     * @var mysqli_connect 로 부터 리턴 받음
     */
    protected $_mysqli;
    /**
     * 씨엠에스코리아 인증 클래스
     *
     * @var Cmskorea_Board_Auth
     */
    protected $_auth;
    
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
     * 글을 작성한다.
     *
     * @throws Exception 필수 항목을 입력하지 않았을 경우
     *                   이름 작성 조건을 지키지않았을 경우
     * @param array 작성할 내용
     *        array(
     *            'memberPk' => '작성자고유키'
     *            'title'   => '제목',
     *            'writer'  => '작성자',
     *            'content' => '내용'
     *        )
     * @return 글번호
     */
    public function addContent(array $datas) {
        if (!$datas['title'] || !$datas['writer'] || !$datas['content']) {
            throw new Exception("필수 항목을 입력해주세요.");
        } else if (!preg_match("/[가-힣A-Za-z]+$/", $datas['writer'])) {
            throw new Exception("이름은 한글, 영문, 숫자만 입력할 수 있습니다.");
        } else {
            $fTitle = mysqli_real_escape_string($this->_mysqli, $datas['title']);
            $fWriter = mysqli_real_escape_string($this->_mysqli, $datas['writer']);
            $fContent = mysqli_real_escape_string($this->_mysqli, $datas['content']);
            
            $sql = "INSERT INTO board(memberPk, title, writer, content, insertTime, updateTime) VALUES ('{$datas['memberPk']}', '{$fTitle}', '{$fWriter}', '{$fContent}', now(), now())";
            $res = mysqli_query($this->_mysqli, $sql);
            $insertedNum = mysqli_insert_id($this->_mysqli);
        }
        
        return $insertedNum;
    }

    /**
     * 내용을 수정한다.
     *
     * @param array 수정할 내용 (글번호 포함)
     *        array(
     *            'no'      => '글번호',
     *            'title'   => '제목',
     *            'writer'  => '작성자',
     *            'content' => '내용'
     *        )
     * @return boolean
     */
    public function editContent(array $datas) {
        // updateTime 수정
        return true;
    }

    /**
     * 을 삭제한다.
     *
     * @param number 글번호
     * @return boolean
     */
    public function delContent($no) {
        return true;
    }

    /**
     * 글을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 데이터 전체
     */
    public function getContent($no) {
        return array();
    }

    /**
     * 조건에 해당하는 글들을 리턴한다.
     *
     * @param array 조회조건(모든 글을 리턴하는 경우 빈배열)
     * @return array 글 내용을 제외한 모든 데이터
     */
    public function getContents(array $conditions) {
        return array();
    }

    /**
     * 업로드된 파일을 등록한다.
     *
     * @param number 게시판 고유키
     * @param array  $_FILES 함수의 내용
     * @return boolean
     */
    public function addFile($boardPk, array $fileInfos) {
        return true;
    }

    /**
     * 게시물에 업로드된 파일들을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 파일데이터
     */
    public function getFiles($boardPk) {
        return array();
    }

    /**
     * 업로드 한 파일을 삭제한다.
     *
     * @param number 파일고유키
     * @return boolean
     */
    public function delFile($filePk) {
        return true;
    }
}

