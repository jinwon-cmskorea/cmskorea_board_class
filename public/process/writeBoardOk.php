<?php
/*
 * @brief 게시글 작성 동작을 수행하는 페이지
 */
session_start();

require_once __DIR__ . '/../AutoLoad.php';

$auth = new Cmskorea_Board_Auth(DBHOST, USERNAME, USERPW, DBNAME);
$board = new Cmskorea_Board_Board(DBHOST, USERNAME, USERPW, DBNAME);

//로그인하지 않은 유저가 접근하면 로그인 페이지로 리다이렉션
if (!$auth->isLogin()) {
    echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
    echo "<script type=\"text/javascript\">document.location.href='../view/login.php';</script>";
}

//작성한 게시글 정보를 배열에 삽입
$addContentArr = array(
    'memberPk'  => $_POST['memberPk'],
    'title'     => $_POST['title'],
    'writer'    => $_POST['writer'],
    'content'   => $_POST['content']
);

//업로드한 파일에 문제가 있을 경우, 출력할 에러문 생성
$fileStatus = array(
    '1'         => '허용되지 않은 확장자의 파일입니다.',
    '2'         => '파일의 용량이 너무 큽니다.'
);
//파일 정보를 담을 배열
$fileArrays = array();
//허용된 확장자들이 포함되어있는 배열 생성
$allowFiles = array(
    'jpeg', 'jpg', 'gif', 'png', 'pdf'
);

/**
 * 파일 검사 통과유무를 저장하는 변수
 * Default 상태는 true, 문제가 있으면 false로 바뀜
 */
$uploadStatus = true;

for ($i = 1; $i <= count($_FILES); $i++) {
    $status = 0;
    if (isset($_FILES['inputFile'.$i]['name']) && $_FILES['inputFile'.$i]['name']) {
        $file = $_FILES['inputFile'.$i];
        
        if (isset($file['type']) && $file['type']) {
            $fileType = explode('/', $file['type']);
            
            //파일 업로드 시 허용된 mime(jpg 등) 타입이 아니면 '허용되지 않은 확장자' 메세지 출력
            if (!in_array($fileType[1], $allowFiles)) {
                $status = 1;
            }
        } else {
            $status = 1;
        }
        
        //파일 업로드 시 3MB 초과하면 '너무 큰 용량' 메세지 출력
        if ($file['size'] > 3145728) {
            $status = 2;
        }
        //파일 검사시, 에러가 있으면 에러문 리턴
        if ($status) {
            $uploadStatus = false;
            echo "<script type=\"text/javascript\">alert('{$fileStatus[$status]}')</script>";
            echo "<script type=\"text/javascript\">history.back(-1)</script>";
            exit;
        } else { //통과한 경우, content를 담은 배열을 추가
            $file['content'] = file_get_contents($file['tmp_name']);
            array_push($fileArrays, $file);
        }
    }
}

/*
 * 업로드 파일이 이상 없으면 게시글 생성 후 파일 업로드, 성공 메세지 출력
 * 실패시 뒤로가기
 */
if ($uploadStatus) {
    //게시글 작성 코드. 성공하면 게시글 번호를 리턴받고, 실패하면 설정된 예외문 호출 뒤 뒤로 감
    try {
        //게시글 작성 메소드 호출
        $boardPk = $board->addContent($addContentArr);
        for ($i = 0; $i < count($fileArrays); $i++) {
            $board->addFile($boardPk, $fileArrays[$i]);
        }
        echo "<script>location.href='../view/boardList.php?message=success'</script>";
    } catch (Exception $e) {
        echo "<script type=\"text/javascript\">alert('{$e->getMessage()}')</script>";
        echo "<script type=\"text/javascript\">history.back(-1)</script>";
    }
}