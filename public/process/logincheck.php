<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/configs/dbconfigs.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/library/Cmskorea/Board/Auth.php';

if (!session_id()) {
    session_start();
}

//로그인 체크 데이터 검색
if (isset($_POST['name']) && isset($_POST['password'])) {
    $loginId = $_POST['name'];
    $loginPw = $_POST['password'];
    
    $authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
    $result = $authDBclass->authenticate($loginId, $loginPw);
    
    if (empty($result)) {
        header("location:../view/board/boardlist.php");
    } else {
        echo "<script>
            alert('{$result}');
            location.replace('../view/login.php');
        </script>";
    }
} else {
    echo "전달 받은 값이 없습니다!";
}
/* if (empty($authDBclass->authenticate('test', 'test1'))) {
    echo "빈값 확인(로그인 OK)";
} else {echo "값 있음 확인(로그인 NOT)";} */
//echo $authDBclass->authenticate('test', 'tes');
?>