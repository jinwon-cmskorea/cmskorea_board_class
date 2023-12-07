<?php
/*
 * 진입 페이지 최초 접근은 이 페이지를 통해 진입하여 로그인이 필요한경우 로그인 페이지로, 로그인이 이미 된 경우 리스트 페이지로 이동한다.
 */
require_once './process/autoload.php';
if (!session_id()) {
    session_start();
}
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
if ($authDBclass->isLogin()) {
    header("location:./view/board/boardlist.php");
} else {
    header("location:./view/login.php");
}