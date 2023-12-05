<?php
/**
 * CMSKOREA BOARD
 *
 * @category Cmskorea
 * @package  Board
 */
//require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/configs/dbconfigs.php';
/**
 * 씨엠에스코리아 게시판 클래스
 *
 * @category Cmskorea
 * @package  Board
 */
class Cmskorea_Board_Board {
    protected $db;
    
    public function __construct($host, $userid, $password, $database) {
        $this->db = mysqli_connect($host, $userid, $password, $database);
        if ($this->db) {
            return $this->db;
        } else {
            return mysqli_error($this->db);
        }
    }
    
    /**
     * 글을 작성한다.
     *
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
        $query = "INSERT INTO board (memberPk, title, writer, content, insertTime, updateTime) VALUES" . "( ". $datas['memberPk'] ." ,'". $datas['title'] ."' ,'". $datas['writer'] ."', '" . $datas['content'] . "' , now(), now())";
        $result = mysqli_query($this->db, $query);
        if ($result) {
            return mysqli_insert_id($this->db);
        }
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
        $query = "UPDATE board SET title='" . $datas['title'] . "', writer='" . $datas['writer'] . "', content='"  . $datas['content'] . "', updateTime=now() WHERE pk=" . $datas['no'] . ";";
        $result = mysqli_query($this->db, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 글을 삭제한다.
     *
     * @param number 글번호
     * @return boolean
     */
    public function delContent($no) {
        $result = mysqli_query($this->db,"DELETE FROM board WHERE pk=" . $no . ";");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 글을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 데이터 전체
     */
    public function getContent($no) {
        $result = mysqli_query($this->db,"SELECT * FROM board WHERE pk=" . $no . ";");
        $row = mysqli_fetch_assoc($result);
        return $row;
    }

    /**
     * 조건에 해당하는 글들을 리턴한다.
     *
     * @param array 조회조건(모든 글을 리턴하는 경우 빈배열)
     * @return array 글 내용을 제외한 모든 데이터
     */
    public function getContents(array $conditions) {
        $query = "select * from board";
        //검색, 정렬 배열 값 꺼내기
        if (array_key_exists('searchInput', $conditions)) {
            $query .= " where " . $conditions["searchTag"] . " LIKE '%" . $conditions["searchInput"] . "%'";
        }
        if (array_key_exists('orderName', $conditions)) {
            $query .= " order by " . $conditions["orderName"] . " " . $conditions["sort"];
        }
        $query .=" limit " . (($conditions['start_list'] - 1) * $conditions['last_list']) . ", " . $conditions['last_list'] . ";";
        $result =  mysqli_query($this->db,$query);
        if ($result) {
            return $result;
        } else {
            return mysqli_error($this->db);
        }
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

