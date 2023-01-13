<?php 
/*
 * @brief 게시글 삭제 동작을 수행하는 페이지
 */
header("Content-Type: application/json");

require_once __DIR__ . '/../AutoLoad.php';

$board = new Cmskorea_Board_Board();

if (isset($_POST['pk']) && $_POST['pk']) {
    $pk = $_POST['pk'];
}

if (isset($pk) && $pk) {
    $delRes = $board->delContent($pk);
    if ($delRes) {
        $status = 1;
    } else {
        $status = 0;
    }
    
    $receive = array('status' => $status);
    echo json_encode($receive, JSON_UNESCAPED_UNICODE);
}