<?php
require_once './autoload.php';

$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
//파일 삭제 (ajax)
try {
if (isset($_POST['deletePk']) && $_POST['deletePk']) {
    $rs = $boardDBclass->delFile($_POST['deletePk']);
    if (!$rs) {
        echo " DB query 실패했습니다!";
    }
} else {
    echo " 삭제할 파일 값이 없습니다!";
}
} catch (Exception $e) {
    echo $e->getMessage();
}