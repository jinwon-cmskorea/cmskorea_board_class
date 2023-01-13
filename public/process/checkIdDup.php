<?php
/*
 * @brief 아이디 중복 검사를 체크하는 페이지
 */
header("Content-Type: application/json");

require_once __DIR__ . '/../AutoLoad.php';
require_once __DIR__ . '/../../configs/dbConfig.php';

$connect = mysqli_connect(DBHOST, USERNAME, USERPW, DBNAME);
if (!$connect) {
    die("DB 접속중 문제가 발생했습니다. : ".mysqli_connect_error());
}

$userId = $_POST['userId'];

if (isset($userId) && $userId) {
    $fUserId = mysqli_real_escape_string($connect, $userId);
    
    $sql = "SELECT COUNT(id) AS count FROM member WHERE id='{$fUserId}'";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_fetch_assoc($res);
    
    if ($count['count'] > 0) {
        $status = 1;
    } else {
        $status = 0;
    }
    
    $receive = array('status' => $status);
    echo json_encode($receive, JSON_UNESCAPED_UNICODE);
}