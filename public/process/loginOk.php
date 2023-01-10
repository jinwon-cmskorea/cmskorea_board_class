<?php
/*
 * @brief 로그인 기능을 작업하는 파일 
 */
session_start();
require_once __DIR__ . '/../AutoLoad.php';

//Auth 인스턴스 생성
$auth = new Cmskorea_Board_Auth();

//form 으로 부터 넘어온 id, pw를 확인
$res = $auth->authenticate($_POST['userId'], $_POST['userPw']);

//로그인 성공시 빈값을 리턴받음, 실패시 불능 메세지를 리턴 받음
if ($res) {
    echo "<script type=\"text/javascript\">alert('.$res.')</script>";
    echo "<script type=\"text/javascript\">history.back(-1)</script>";
} else {
    header('location: ../view/boardList.php');
}