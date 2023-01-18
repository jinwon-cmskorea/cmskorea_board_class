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
    //게시글을 삭제 전에, 게시글에 첨부된 파일이 존재하는 경우 파일부터 먼저 삭제
    $fileArrays = $board->getFiles($pk);
    if (count($fileArrays) > 0) {
        for ($i = 0; $i < count($fileArrays); $i++) {
            $fileDelRes = $board->delFile($fileArrays[$i]['pk']);
            
            //파일 삭제가 정상적을 되지 않았으면 게시물 삭제하면 안됨. 에러가 있다고 return
            if (!$fileDelRes) {
                $status = 0;
                $receive = array('status' => $status);
                echo json_encode($receive, JSON_UNESCAPED_UNICODE);
                return ;
            }
        }
    }
    
    $delRes = $board->delContent($pk);
    if ($delRes) {
        $status = 1;
    } else {
        $status = 0;
    }
    
    $receive = array('status' => $status);
    echo json_encode($receive, JSON_UNESCAPED_UNICODE);
}