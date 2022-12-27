<?php
/**
 * CMSKOREA BOARD
 *
 * @category Cmskorea
 * @package  Board
 */
/**
 * 씨엠에스코리아 사용자 인증 클래스
 *
 * @category Cmskorea
 * @package  Board
 */
class Cmskorea_Board_Auth {
    /**
     * 씨엠에스코리아 사용자 클래스
     *
     * @var Cmskorea_Baord_Member
     */
    protected $_member;

    /**
     * 로그인 인증
     *
     * @param string 아이디
     * @param string 비밀번호
     * @return boolean
     */
    public function authenticate($id, $pw) {
        return true;
    }

    /**
     * 로그아웃
     *
     * @return boolean
     */
    public function logout() {
        return true;
    }
}

