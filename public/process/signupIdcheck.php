<?php
require_once './autoload.php';
//회원가입 id KeyUp 이벤트
$memberDBclass = new Cmskorea_Board_Member(HOST, USERID, PASSWORD, DATABASE);
$result = $memberDBclass->getMember($_POST['id']);
if (empty($result)) {
    echo true;
} else {
    echo false;
}