<?php
require_once './autoload.php';
$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
function alert_replace ($message, $location) {
    echo "<script>
                alert(\"'{$message}'\");
                location.replace('{$location}');
            </script>";
}

if ((isset($_GET['boardPk']) && $_GET['boardPk']) && (isset($_GET['filePk']) && $_GET['filePk'])) {
    $boardFileData = $boardDBclass->getFiles($_GET['boardPk']);
    $fileData = '';
    foreach ($boardFileData as $value) {
        if ($value['pk'] == $_GET['filePk']) {
            $fileData = $value;
        }
    }
    //임시 파일 저장
    $filepath = "./../../datas/";
    $filename = $filepath.iconv('utf-8','euc-kr', $fileData['filename']);
    file_put_contents($filename, $fileData['content']);
    //파일 다운로드 header 지정 및 다운로드
    header('Pragma: public');
    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
    header("Content-Length: ".$fileData['size']);
    header("Content-Disposition: attachment; filename=".iconv('utf-8','euc-kr', $fileData['filename']));
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0');
    ob_clean();
    flush();
    readfile($filename);
    //임시 파일 삭제
    if (is_file($filename)) {
        unlink($filename);
    }
    exit;
} else {
    if (isset($_GET['boardPk']) && $_GET['boardPk']) {
        alert_replace("다운로드 받을 파일 입력값이 없습니다! 게시글로 돌아갑니다.", "../view/board/boardview.php?post=" . $_GET['boardPk']);
    } else {
        alert_replace("다운로드 받을 파일 입력값이 없습니다! 게시글 목록으로 돌아갑니다.", "../view/board/boardlist.php");
    }
}
