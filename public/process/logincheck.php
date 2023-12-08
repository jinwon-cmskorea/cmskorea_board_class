<?php
require_once './autoload.php';

if (!session_id()) {
    session_start();
}

//로그인 체크 데이터 검색
if ((isset($_POST['name']) && $_POST['name']) && (isset($_POST['password']) && $_POST['password'])) {
    $loginId = $_POST['name'];
    $loginPw = $_POST['password'];
    
    $authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
    $result = $authDBclass->authenticate($loginId, $loginPw);
    
    if (empty($result)) {
        echo "<script>
            location.replace('../view/board/boardlist.php');
        </script>";
    } else {
        echo "<script>
            alert('{$result}');
            location.replace('../view/login.php');
        </script>";
    }
} else {
    echo "전달 받은 값이 없습니다!";
}
?>