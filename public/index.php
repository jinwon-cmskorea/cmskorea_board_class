<?php
/*
 * 진입 페이지 최초 접근은 이 페이지를 통해 진입하여 로그인이 필요한경우 로그인 페이지로, 로그인이 이미 된 경우 리스트 페이지로 이동한다.
 */
session_start();
include __DIR__ . '/AutoLoad.php'; 

$auth = new Cmskorea_Board_Auth();
if (!$auth->isLogin()) {
    header('location: ../view/login.php');
} else {
    header('location: ../view/boardList.php');
}
