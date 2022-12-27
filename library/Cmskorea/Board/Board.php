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
     * 글을 작성한다.
     *
     * @param string 작성자 아이디
     * @param array 작성할 내용
     *        array(
     *            'title'   => '제목',
     *            'content' => '내용'
     *        )
     * @return 글번호
     */
    public function addContent($id, array $datas) {
        return 1;
    }

    /**
     * 내용을 수정한다.
     *
     * @param array 수정할 내용 (글번호 포함)
     *        array(
     *            'no'      => '글번호',
     *            'content' => '내용'
     *        )
     * @return boolean
     */
    public function editContent(array $datas) {
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
}

