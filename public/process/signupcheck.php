<?php
require_once './autoload.php';

$memberDBclass = new Cmskorea_Board_Member(HOST, USERID, PASSWORD, DATABASE);

//alert 경고창, 페이지 이동 함수
function alertReplace($message, $location) {
    echo "<script>
                alert(\"'{$message}'\");
                location.replace('{$location}');
            </script>";
}
//회원가입 체크
if ((isset($_POST['memberId']) && $_POST['memberId']) && (isset($_POST['memberPw']) && $_POST['memberPw']) 
    && (isset($_POST['memberName']) && $_POST['memberName']) && (isset($_POST['memberTel']) && $_POST['memberTel'])) {
    
    $signupArr = array();
    $signupArr['id'] = $_POST['memberId'];
    $signupArr['pw'] = $_POST['memberPw'];
    $signupArr['name'] = $_POST['memberName'];
    $signupArr['telNumber'] = $_POST['memberTel'];
    
    try {
        if ($memberDBclass->getMember($signupArr['id'])) {
            alertReplace("중복된 아이디입니다! 다시 작성해주세요.", "../view/signup.php");
        } else {
            $result = $memberDBclass->registMember($signupArr);
            alertReplace("회원 가입이 완료되었습니다!", "../view/login.php");
        }
    } catch (Exception $e) {
        alertReplace($e->getMessage(), "../view/signup.php");
    }
} else {
    alertReplace("전달받은 값이 없습니다! 다시 작성해주세요.", "../view/signup.php");
}
?>