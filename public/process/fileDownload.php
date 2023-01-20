<?php 
/**
 * 파일 다운로드를 수행하는 페이지
 * 
 * @param get으로 받아온 게시글번호
 * @param get으로 받아온 파일명
 */
require_once __DIR__ . '/../AutoLoad.php';

$board = new Cmskorea_Board_Board(DBHOST, USERNAME, USERPW, DBNAME);

if (isset($_GET['pk']) && $_GET['pk']) {
    $getPk = $_GET['pk'];
}

if (isset($_GET['filename']) && $_GET['filename']) {
    $getFilename = $_GET['filename'];
}

if (isset($getFilename) && $getFilename && isset($getPk) && $getPk) {
    $resultFiles = $board->getFiles($getPk);
    
    for ($i = 0; $i < count($resultFiles); $i++) {
        if ($resultFiles[$i]['filename'] == $getFilename) {
            /*
             * datas 폴더에 파일을 조립하고 저장
             * 한글 파일명의 경우, 그대로 사용하면 인식을 못해서 에러 발생
             * 그래서 iconv 함수를 이용해 인코딩을 변경해줌
             */
            $tmpFileName = iconv('utf-8', 'cp949', $resultFiles[$i]['filename']);
            $saveDir = __DIR__ . '/../../datas/' . $tmpFileName;
            file_put_contents($saveDir, $resultFiles[$i]['content']);
            
            //파일 다운로드 기능을 위한 헤더 설정
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='. $tmpFileName);
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '. $resultFiles[$i]['fileSize']);
            header('Expires: 0');
            header('Pragma: public');
            
            ob_clean();//출력 없이 버퍼만 비우고, 종료는 안함
            flush();//버퍼에 저장되어있는 내용을 브라우저로 출력후 버퍼를 비움
            readfile($saveDir);
            unlink($saveDir);
            
            exit;
        }
    }
}