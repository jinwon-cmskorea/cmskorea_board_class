<?php
/**
 * 게시글 수정 동작을 수행하는 페이지
 */
session_start();

require_once __DIR__ . '/../AutoLoad.php';

$auth = new Cmskorea_Board_Auth();
$board = new Cmskorea_Board_Board();

//로그인하지 않은 유저가 접근하면 로그인 페이지로 리다이렉션
if (!$auth->isLogin()) {
    echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
    echo "<script type=\"text/javascript\">document.location.href='../view/login.php';</script>";
}

//post 로 받아온 값들을 배열에 넣어줌
$editDatas = array(
    'no'        => $_POST['no'],//게시글 고유 번호
    'title'     => $_POST['title'],
    'writer'    => $_POST['writer'],
    'content'   => $_POST['content']
);

//받아온 값들을 토대로 게시글 수정, 문제가 있을 경우 exception 출력
try {
    $editRes = $board->editContent($editDatas);
} catch (Exception $e) {
    echo "<script type='text/javascript'>alert('{$e->getMessage()}')</script>";
    echo "<script type='text/javascript'>history.back(-1)</script>";
}

$uploadStatus1 = true;
$uploadStatus2 = true;

//파일이 존재하는 지 확인
$fileArrays = $board->getFiles($editDatas['no']);

//업로드 파일이 존재하면 파일 업로드 메소드 호출 및 테이블에 삽입
if (isset($_FILES['inputFile1']['name']) && $_FILES['inputFile1']['name']) {
    $file1 = $_FILES['inputFile1'];
    
    //만약 첨부파일 갯수가 이미 2개이면 가장 오래된 파일 삭제 후 새 파일 삽입
    if (count($fileArrays) >= 2) {
        $res = $board->delFile($fileArrays[0]['pk']);
        if (!$res) {
            echo "<script type=\"text/javascript\">alert('기존 파일 삭제 중 문제가 발생했습니다.')</script>";
            echo "<script type=\"text/javascript\">history.back(-1)</script>";
        }
    }
    $file1['content'] = file_get_contents($file1['tmp_name']);
    $uploadStatus1 = $board->addFile($editDatas['no'], $file1);
}

//파일이 존재하는 지 확인
$fileArrays = $board->getFiles($editDatas['no']);

//업로드 파일이 존재하면 파일 업로드 메소드 호출 및 테이블에 삽입
if (isset($_FILES['inputFile2']['name']) && $_FILES['inputFile2']['name']) {
    $file2 = $_FILES['inputFile2'];
    
    //만약 첨부파일 갯수가 이미 2개이면 가장 오래된 파일 삭제 후 새 파일 삽입
    if (count($fileArrays) >= 2) {
        $res = $board->delFile($fileArrays[0]['pk']);
        if (!$res) {
            echo "<script type=\"text/javascript\">alert('기존 파일 삭제 중 문제가 발생했습니다.')</script>";
            echo "<script type=\"text/javascript\">history.back(-1)</script>";
        }
    }
    $file2['content'] = file_get_contents($file2['tmp_name']);
    $uploadStatus2 = $board->addFile($editDatas['no'], $file2);
}

if ($editRes && $uploadStatus1 && $uploadStatus2) {
    echo "<script type=\"text/javascript\">location.href='../view/boardList.php?message=editSuccess'</script>";
} else {
    echo "<script type=\"text/javascript\">alert('게시글 수정 중 문제가 발생했습니다.')</script>";
    echo "<script type=\"text/javascript\">history.back(-1)</script>";
}