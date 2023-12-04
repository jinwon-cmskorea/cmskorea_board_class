<?php
require_once "../../configs/dbconn.php";

if (!session_id()) {
    session_start();
}
$loginId = $_POST['name'];
$loginPw = $_POST['password'];
$md5Pw = md5($loginPw);

//로그인 체크 데이터 검색
function login_data_search($id, $pw) {
    $logincheckDBclass = new DBconn();
    $query = "SELECT id FROM auth_identity where id='" . $id . "' and pw='" . $pw . "';";
    $search = mysqli_fetch_all(mysqli_query($logincheckDBclass->getDBconnect(), $query));
    
    if (!(empty($search))) {
        return  true;
    } else {
        return  false;
    }
}
if(login_data_search($loginId, $md5Pw)) {
    $_SESSION['userName'] = $loginId;
    header("location:../view/board/boardlist.php");
} else {
    echo "<script>
            alert('아이디 또는 비밀번호가 일치하지 않습니다!');
            location.replace('../view/login.php');
        </script>";
}
?>