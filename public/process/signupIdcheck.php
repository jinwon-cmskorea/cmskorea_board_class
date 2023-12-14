<?php
require_once './autoload.php';

//회원가입 id 중복 KeyUp 체크 (ajax)
$memberDBclass = new Cmskorea_Board_Member(HOST, USERID, PASSWORD, DATABASE);
$result = $memberDBclass->getMember($_POST['id']);
if (!isset($result) || empty($result)) {
    echo true;
} else {
    echo false;
}