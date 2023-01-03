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
class Cmskorea_Baord_Member {
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
        return array();
    }

    /**
     * 로그인 인증
     *
     * @param string 아이디
     * @param string 비밀번호
     * @return string 로그인 성공 시 빈값|로그인 불능 시 불능메시지
     */
    public function authenticate($id, $pw) {
        return '';
    }
}

