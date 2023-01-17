<?php
/**
 * 파일 삭제를 수행하는 페이지
 */
header("Content-Type: application/json");

require_once __DIR__ . '/../AutoLoad.php';

$board = new Cmskorea_Board_Board();

//post로 삭제할 파일의 pk를 받아옴
if (isset($_POST['filePk']) && $_POST['filePk']) {
    $filePk = $_POST['filePk'];
}

if (isset($filePk) && $filePk) {
    //정상적으로 받아왔다면 파일 삭제 수행
    $res = $board->delFile($filePk);
    if ($res) {
        $status = 1;
    } else {
        $status = 0;
    }
    
    //json 형태로 결과 리턴
    $receive = array('status' => $status);
    echo json_encode($receive, JSON_UNESCAPED_UNICODE);
}