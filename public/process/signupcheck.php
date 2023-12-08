<?php
require_once './autoload.php';

$memberDBclass = new Cmskorea_Board_Member(HOST, USERID, PASSWORD, DATABASE);

function alert_replace ($message, $location) {
    echo "<script>
                alert('{$message}');
                location.replace('{$location}');
            </script>";
}

if ((isset($_POST['memberId']) && $_POST['memberId']) && (isset($_POST['memberPw']) && $_POST['memberPw']) 
    && (isset($_POST['memberName']) && $_POST['memberName']) && (isset($_POST['memberTel']) && $_POST['memberTel'])) {
    
    $signupArr = array();
    $signupArr['id'] = $_POST['memberId'];
    $signupArr['pw'] = $_POST['memberPw'];
    $signupArr['name'] = $_POST['memberName'];
    $signupArr['telNumber'] = $_POST['memberTel'];
    
    try {
        if ($memberDBclass->getMember($signupArr['id'])) {
            alert_replace ("중복된 아이디입니다! 다시 작성해주세요.", "../view/signup.php");
        } else {
            $result = $memberDBclass->registMember($signupArr);
            alert_replace ("회원 가입이 완료되었습니다!", "../view/login.php");
        }
    } catch (Exception $e) {
        alert_replace ($e->getMessage(), "../view/signup.php");
    }
} else {
    alert_replace ("전달받은 값이 없습니다! 다시 작성해주세요.", "../view/signup.php");
}
?>