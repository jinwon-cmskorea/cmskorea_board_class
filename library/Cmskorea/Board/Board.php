<?php
/**
 * CMSKOREA BOARD
 *
 * @category Cmskorea
 * @package  Board
 */
/**
 * 씨엠에스코리아 게시판 클래스
 *
 * @category Cmskorea
 * @package  Board
 */
class Cmskorea_Board_Board {
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
        //전달받은 값 확인
        if ((!$datas['memberPk'] && empty($datas['memberPk'])) || (!$datas['title'] && empty($datas['title']))
                || (!$datas['writer'] && empty($datas['writer']))) {
            throw new Exception("게시글 등록 오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        
        $title = mysqli_real_escape_string($this->_db, $datas['title']);
        $writer = mysqli_real_escape_string($this->_db, $datas['writer']);
        $strip = mysqli_real_escape_string($this->_db, strip_tags($datas['content'], '<br>'));
        
        $query = "INSERT INTO board (memberPk, title, writer, content, insertTime, updateTime) VALUES" . "( ". $datas['memberPk'] ." ,'". $title ."' ,'". $writer ."', '" . $strip . "' , now(), now())";
        $result = mysqli_query($this->_db, $query);
        if ($result) {
            return mysqli_insert_id($this->_db);
        } else {
            throw new Exception("게시글 등록 오류 확인 : " . mysqli_error($this->_db));
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
        //전달받은 값 확인
        if ((!$datas['no'] && empty($datas['no'])) || (!$datas['title'] && empty($datas['title']))
                || (!$datas['writer'] && empty($datas['writer']))) {
                    throw new Exception("게시글 수정 오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        // updateTime 수정
        $title = mysqli_real_escape_string($this->_db, $datas['title']);
        $writer = mysqli_real_escape_string($this->_db, $datas['writer']);
        $strip = mysqli_real_escape_string($this->_db, strip_tags($datas['content'], '<br>'));
        
        $query = "UPDATE board SET title='" . $title . "', writer='" . $writer . "', content='"  . $strip . "', updateTime=now() WHERE pk=" . $datas['no'] . ";";
        $result = mysqli_query($this->_db, $query);
        if ($result) {
            if (mysqli_affected_rows($this->_db) > 0) {
                return true;
            } else {
                return false;
            }
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
        $fileCheck = mysqli_query($this->_db, "SELECT * FROM file WHERE boardPk=" . $no . ";");
        if ($fileCheck->num_rows > 0) {
            foreach ($fileCheck as $value) {
                $rs = $this->delFile($value['pk']);
            }
        }
        $replyCheck = mysqli_query($this->_db, "SELECT * FROM board_reply WHERE boardPk=" . $no . ";");
        if ($replyCheck->num_rows > 0) {
            foreach ($replyCheck as $value) {
                $rs = $this->delReply($value['pk']);
                
            }
        }
        mysqli_query($this->_db,"DELETE FROM board WHERE pk=" . $no . ";");
        return true;
    }

    /**
     * 글을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 데이터 전체
     */
    public function getContent($no) {
        $result = mysqli_query($this->_db,"SELECT * FROM board WHERE pk=" . $no . ";");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            throw new Exception("게시글 조회 오류 내용 : " . mysqli_error($this->_db));
        }
    }

    /**
     * 조건에 해당하는 글들을 리턴한다.
     *
     * @param array 조회조건(모든 글을 리턴하는 경우 빈배열)
     *        array(
     *            'searchTag'   => '검색 조건',
     *            'searchInput' => '검색어',
     *            'orderName'   => '정렬 조건',
     *            'sort'        => '정렬 차순'
     *            'start_list'  => '페이지'
     *        )
     * @return array 글 내용을 제외한 모든 데이터
     *         array(
     *            'pk'      => '글번호',
     *            'memberPk' => '회원고유키',
     *            'title'   => '제목',
     *            'writer'  => '작성자',
     *            'insertTime'  => '등록시간',
     *            'updateTime'  => '변경시간'
     *        )
     */
    public function getContents(array $conditions) {
        $query = "SELECT pk, memberPk, title, writer, insertTime, updateTime FROM board";
        //검색, 정렬 배열 값 존재 확인 후 query 추가
        if (array_key_exists('searchTag', $conditions) && array_key_exists('searchInput', $conditions)) {
            $query .= " WHERE " . $conditions["searchTag"] . " LIKE '%" . $conditions["searchInput"] . "%'";
        }
        if (array_key_exists('orderName', $conditions) && array_key_exists('sort', $conditions)) {
            $query .= " ORDER BY " . $conditions["orderName"] . " " . $conditions["sort"];
        }
        if (array_key_exists('start_list', $conditions)) {
            $query .=" LIMIT " . (($conditions['start_list'] - 1) * 10) . ", 10";
        }
        $result =  mysqli_query($this->_db, $query. ";");
        if ($result) {
            return $result;
        } else {
            throw new Exception("게시글 목록 조회 오류 내용 : " . mysqli_error($this->_db));
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
        if (!isset($boardPk) || empty($fileInfos)) {
            throw new Exception("파일 업로드 오류 확인 : 전달받은 값 에러! 부족한 값이 존재합니다.");
        }
        //파일 업로드 용량, 확장자, 오류 체크
        $ext = explode('/', $fileInfos['type'])[1];
        $extOk = array('jpeg','png','gif','pdf');
        if (!in_array($ext, $extOk)) {
            throw new Exception("파일 업로드 오류 내용 : 업로드할 수 없는 파일 확장자입니다! 확장자 : " . $fileInfos['type']);
        }
        if ($fileInfos['size'] > (3 * 1024 * 1024)) {
            throw new Exception("파일 업로드 오류 내용 : 파일 용량은 최대 3MB 입니다! 용량 : " . $fileInfos['size']);
        }
        if ($fileInfos["error"] > 0) {
            throw new Exception("파일 업로드 오류 내용 : 에러 코드 " . $fileInfos["error"]);
        }
        //파일 정보 업로드
        $query = "INSERT INTO file (boardPk, filename, fileType, fileSize, insertTime) VALUES" . "( ". $boardPk ." ,'". $fileInfos['name'] ."' ,'". $ext ."', '" . $fileInfos['size'] . "' , now())";
        $rs = mysqli_query($this->_db,$query);
        if ($rs) {
            //임시 파일 저장
            $filepath = FILEPATH;
            $filename = $filepath.iconv("UTF-8", "EUC-KR",$fileInfos['name']);
            move_uploaded_file($fileInfos['tmp_name'], $filename);
            //파일 내용 업로드
            $content = mysqli_real_escape_string($this->_db, file_get_contents($filename));
            $filePk = mysqli_insert_id($this->_db);
            $query = "INSERT INTO file_details (filePk, content) VALUES" . "( ". $filePk ." ,'". $content ."')";
            $rs = mysqli_query($this->_db,$query);
            //임시 파일 삭제
            unlink($filename);
            if (!$rs) {
                throw new Exception('파일 업로드 오류 내용 : 파일 DB 업로드에 실패했습니다!' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
            }
        } else {
            throw new Exception('파일 업로드 오류 내용 : 파일 DB 업로드에 실패했습니다!' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
        }
        return true;
    }

    /**
     * 게시물에 업로드된 파일들을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 파일데이터
     *         array(
     *            'pk'      => '파일고유키',
     *            'boardPk'   => '게시판고유키',
     *            'filename'  => '파일명',
     *            'fileType'  => '파일타입',
     *            'fileSize'  => '파일크기',
     *            'insertTime'  => '등록시간',
     *            'content' => '파일내용'
     *        )
     */
    public function getFiles($boardPk) {
        $query = "SELECT * FROM file WHERE boardPk=" . $boardPk . ";";
        $rs = mysqli_query($this->_db,$query);
        $resultArray = array();
        foreach ($rs as $value) {
            $filePk = $value['pk'];
            $filedata = $value;
            $query = "SELECT content FROM file_details WHERE filePk=" . $filePk . ";";
            $rs = mysqli_fetch_array(mysqli_query($this->_db, $query));
            $filedata['content'] = $rs['content'];
            array_push($resultArray, $filedata);
        }
        return $resultArray;
    }

    /**
     * 업로드 한 파일을 삭제한다.
     *
     * @param number 파일고유키
     * @return boolean
     */
    public function delFile($filePk) {
        mysqli_query($this->_db,"DELETE FROM file WHERE pk=" . $filePk . ";");
        mysqli_query($this->_db,"DELETE FROM file_details WHERE filePk=" . $filePk . ";");
        return true;
    }
    
    /**
     * 댓글을 작성한다.
     * @param array 댓글 입력 내용
     *        array(
     *            'boardPk' => '게시물 번호',
     *            'memberPk' => '작성자고유키',
     *            'content' => '내용',
     *            'insertTime'  => '등록시간'
     *        )
     * @return boolean
     */
    public function addReply(array $datas) {
        if ((!$datas['boardPk'] && empty($datas['boardPk'])) || (!$datas['memberPk'] && empty($datas['memberPk'])) ||  (!$datas['content'] && empty($datas['content']))) {
                    throw new Exception("게시글 등록 오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
                }
        $strip = mysqli_real_escape_string($this->_db, strip_tags($datas['content'], '<br>'));
        
        $query = "INSERT INTO board_reply (boardPk, memberPk, content, insertTime) VALUES" . "( '". $datas['boardPk'] ."' ,'". $datas['memberPk'] ."' ,'". $strip . "' , now())";
        $result = mysqli_query($this->_db, $query);
        if ($result) {
            return true;
        } else {
            throw new Exception("게시글 댓글 등록 오류 확인 : " . mysqli_error($this->_db));
        }
    }
    /**
     * 댓글을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 댓글 데이터 전체, 댓글 작성자 이름
     *         array(
     *            'pk' => '댓글 번호',
     *            'boardPk' => '게시물 번호',
     *            'memberPk' => '작성자고유키',
     *            'content' => '내용',
     *            'name' => '작성자 이름'
     *        )
     */
    public function getReply($boardPk) {
        $result = mysqli_query($this->_db,"SELECT board_reply.*, member.name FROM board_reply JOIN member ON board_reply.memberPk=member.pk WHERE board_reply.boardPk=" . $boardPk . " ORDER BY insertTime DESC;");
        if ($result) {
            return $result;
        } else {
            throw new Exception("게시글 댓글 조회 오류 내용 : " . mysqli_error($this->_db));
        }
    }
    /**
     * 댓글을 삭제한다.
     *
     * @param number 댓글 번호
     * @return boolean
     */
    public function delReply($no) {
        mysqli_query($this->_db, "DELETE FROM board_reply WHERE pk=" . $no . ";");
        return true;
    }
}

