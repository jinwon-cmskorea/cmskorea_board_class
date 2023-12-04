<?php
require_once "../../configs/dbconn.php";

function insert_member() {
    $inputId = $_POST['memberId'];
    $inputPw = $_POST['memberPw'];
    $inputName = $_POST['memberName'];
    $inputTel = $_POST['memberTel'];
    
    $memberDBclass = new DBconn();
    $searchId = $memberDBclass->data_search( "member", "id","id", $inputId);
    if ($searchId) {
        echo "<script>
                alert('중복된 아이디입니다! 다시 작성해주세요.');
                location.replace('../view/signup.php');
            </script>";
    } else {
        $memberDBclass->getDbInsert("member", "(id, name, telNumber, insertTime, updateTime)", "( '". $inputId."' ,'". $inputName."' ,'". $inputTel."' , now(), now())");
        $memberDBclass->getDbInsert("auth_identity", "(id, pw, name, insertTime)", "( '". $inputId ."' ,'". md5($inputPw) ."' ,'". $inputName."' , now())");
        echo "<script>
                alert('회원 가입이 완료되었습니다!');
                location.replace('../view/login.php');
            </script>";
    }
}
if (isset($_POST['memberId']) && isset($_POST['memberPw']) && isset($_POST['memberName']) && isset($_POST['memberTel'])) {
    insert_member();
} else {
    echo "전달 받은 값이 없습니다!";
}
?>