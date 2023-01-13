<?php
/*
 * @brief 게시글 작성 동작을 수행하는 페이지
 */
require_once __DIR__ . '/../AutoLoad.php';

$board = new Cmskorea_Board_Board();

var_dump($_POST['memberPk']);
//작성한 게시글 정보를 배열에 삽입
$addContentArr = array(
    'memberPk'  => $_POST['memberPk'],
    'title'     => $_POST['title'],
    'writer'    => $_POST['writer'],
    'content'   => $_POST['content']
);


//게시글 작성 코드. 성공하면 게시글 번호를 리턴받고, 실패하면 설정된 예외문 호출 뒤 뒤로 감
try {
    //게시글 작성 메소드 호출
    $boardPk = $board->addContent($addContentArr);
} catch (Exception $e) {
    echo "<script type=\"text/javascript\">alert('{$e->getMessage()}')</script>";
    echo "<script type=\"text/javascript\">history.back(-1)</script>";
}

/*
 * 업로드 파일 상태는 디폴트로 true
 * 만약 업로드가 실패하면 false로 바뀜
 */
$uploadStatus1 = true;
$uploadStatus2 = true;

//업로드 파일이 존재하면 파일 업로드 메소드 호출 및 테이블에 삽입
if (isset($_FILES['inputFile1']['name']) && $_FILES['inputFile1']['name']) {
    $file1 = $_FILES['inputFile1'];
    var_dump(is_file($file1['tmp_name']));
    $file1['content'] = file_get_contents($file1['tmp_name']);
    $uploadStatus1 = $board->addFile($boardPk, $file1);
}

if (isset($_FILES['inputFile2']['name']) && $_FILES['inputFile2']['name']) {
    $file2 = $_FILES['inputFile2'];
    $file2['content'] = file_get_contents($file2['tmp_name']);
    $uploadStatus2 = $board->addFile($boardPk, $file2);
}

/*
 * 작성한 게시글 저장에 성공하고, 업로드 파일이 존재할 때 이상없이 업로드했다면 성공 메세지 출력
 * 실패시 뒤로가기
 */
if ($boardPk && $uploadStatus1 && $uploadStatus2) {
    echo "<script>location.href='../view/boardList.php?message=success'</script>";
} else {
//     echo "<script type=\"text/javascript\">alert('게시글 작성 중 문제가 발생했습니다.')</script>";
//     echo "<script type=\"text/javascript\">history.back(-1)</script>";
    echo var_dump($boardPk) . ", " . var_dump($uploadStatus1) . ", " . var_dump($uploadStatus2);
}