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
        if (!isset($datas['memberPk']) || !isset($datas['title']) || !isset($datas['writer'])) {
            throw new Exception("오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        
        $title = mysqli_real_escape_string($this->_db, $datas['title']);
        $writer = mysqli_real_escape_string($this->_db, $datas['writer']);
        $strip = mysqli_real_escape_string($this->_db, strip_tags($datas['content'], '<br>'));
        
        $query = "INSERT INTO board (memberPk, title, writer, content, insertTime, updateTime) VALUES" . "( ". $datas['memberPk'] ." ,'". $title ."' ,'". $writer ."', '" . $strip . "' , now(), now())";
        $result = mysqli_query($this->_db, $query);
        if ($result) {
            return mysqli_insert_id($this->_db);
        } else {
            throw new Exception("오류 확인 : " . mysqli_error($this->_db));
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
        if (!isset($datas['no']) || !isset($datas['title']) || !isset($datas['writer'])) {
                    throw new Exception("오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
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
        $result = mysqli_query($this->_db,"DELETE FROM board WHERE pk=" . $no . ";");
        if ($result) {
            if (mysqli_affected_rows($this->_db) > 0) {
                //**file CASCADE 확인**
                $select = mysqli_fetch_array(mysqli_query($this->_db, "SELECT * FROM file WHERE boardPk=" . $no . ";"));
                if (!is_null($select)) {
                    foreach ($select as $value) {
                        $rs = $this->delFile($value['pk']);
                        if (!$rs) {
                            return false;
                        }
                    }
                }
                //*********************
                return true;
            } else {
                return false;
            }
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
        $result = mysqli_query($this->_db,"SELECT * FROM board WHERE pk=" . $no . ";");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            throw new Exception("오류 내용 : " . mysqli_error($this->_db));
        }
    }

    /**
     * 조건에 해당하는 글들을 리턴한다.
     *
     * @param array 조회조건(모든 글을 리턴하는 경우 빈배열)
     * @return array 글 내용을 제외한 모든 데이터
     */
    public function getContents(array $conditions) {
        $query = "SELECT pk, memberPk, title, writer, views, insertTime, updateTime FROM board";
        //검색, 정렬 배열 값 꺼내기
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
            throw new Exception("오류 내용 : " . mysqli_error($this->_db));
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
            throw new Exception("오류 확인 : 전달받은 값 에러! 부족한 값이 존재합니다.");
        }
        //throw new Exception();
        $ext = explode('/', $fileInfos['type'])[1];
        $extOk = array('jpeg','png','gif','pdf');
        if (!in_array($ext, $extOk)) {
            throw new Exception(" 오류 내용 : 업로드할 수 없는 파일 확장자입니다! 확장자 : " . $fileInfos['type']);
        }
        if ($fileInfos['size'] > (3 * 1024 * 1024)) {
            throw new Exception(" 오류 내용 : 파일 용량은 최대 3MB 입니다! 용량 : " . $fileInfos['size']);
        }
        $query = "INSERT INTO file (boardPk, filename, fileType, fileSize, insertTime) VALUES" . "( ". $boardPk ." ,'". $fileInfos['name'] ."' ,'". $ext ."', '" . $fileInfos['size'] . "' , now())";
        $rs = mysqli_query($this->_db,$query);
        if ($rs) {
            //임시 파일 저장
            $filepath = "./../../datas/";
            $filename = $filepath.iconv("UTF-8", "EUC-KR",$fileInfos['name']);
            move_uploaded_file($fileInfos['tmp_name'], $filename);
            //파일 업로드
            $content = mysqli_real_escape_string($this->_db, file_get_contents($filename));
            $filePk = mysqli_insert_id($this->_db);
            $query = "INSERT INTO file_details (filePk, content) VALUES" . "( ". $filePk ." ,'". $content ."')";
            $rs = mysqli_query($this->_db,$query);
            //임시 파일 삭제
            unlink($filename);
            if (!$rs) {
                throw new Exception(' 오류 내용 : 파일 DB 업로드에 실패했습니다!' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
            }
        } else {
            throw new Exception(' 오류 내용 : 파일 DB 업로드에 실패했습니다!' . mysqli_errno($this->_db) . ":" . mysqli_error($this->_db));
        }
        return true;
    }

    /**
     * 게시물에 업로드된 파일들을 리턴한다.
     *
     * @param number 글번호
     * @return array 글번호에 해당하는 파일데이터
     */
    public function getFiles($boardPk) {
        if (!isset($boardPk)) {
            throw new Exception("오류 확인 : 전달받은 값 에러! 부족한 값이 존재합니다.");
        }
        $query = "SELECT * FROM file WHERE boardPk=" . $boardPk . ";";
        $rs = mysqli_query($this->_db,$query);
        if ($rs) {
            $resultArray = array();
            $index = 0;
            foreach ($rs as $value) {
                $filePk = $value['pk'];
                $filedata = $value;
                $query = "SELECT content FROM file_details WHERE filePk=" . $filePk . ";";
                $rs = mysqli_fetch_array(mysqli_query($this->_db, $query));
                $filedata['content'] = $rs['content'];
                array_push($resultArray, $filedata);
            }
            return $resultArray;
        } else {
            throw new Exception("오류 내용 : " . mysqli_error($this->_db));
        }
    }

    /**
     * 업로드 한 파일을 삭제한다.
     *
     * @param number 파일고유키
     * @return boolean
     */
    public function delFile($filePk) {
        $result = mysqli_query($this->_db,"DELETE FROM file WHERE pk=" . $filePk . ";");
        if ($result) {
            if (mysqli_affected_rows($this->_db) > 0) {
                //file_details CASCADE 확인
                $select = mysqli_fetch_array(mysqli_query($this->_db, "SELECT * FROM file_details WHERE filePk=" . $filePk . ";"));
                if (!is_null($select)) {
                    mysqli_query($this->_db,"DELETE FROM file_details WHERE filePk=" . $filePk . ";");
                    if (mysqli_affected_rows($this->_db) > 0) {
                        return true;
                    } else {
                        return false;
                    }
                }
                //************************
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

