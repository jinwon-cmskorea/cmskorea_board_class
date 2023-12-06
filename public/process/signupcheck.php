<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/configs/dbconfigs.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/library/Cmskorea/Board/Member.php';

if (isset($_POST['memberId']) && isset($_POST['memberPw']) && isset($_POST['memberName']) && isset($_POST['memberTel'])) {
    $signupArr = array();
    $signupArr['id'] = $_POST['memberId'];
    $signupArr['pw'] = $_POST['memberPw'];
    $signupArr['name'] = $_POST['memberName'];
    $signupArr['telNumber'] = str_replace('-', '', $_POST['memberTel']);
    
    $memberDBclass = new Cmskorea_Board_Member(HOST, USERID, PASSWORD, DATABASE);
    $result = $memberDBclass->registMember($signupArr);
    if (!is_string($result)) {
        echo "<script>
                alert('회원 가입이 완료되었습니다!');
                location.replace('../view/login.php');
            </script>";
    } else {
        echo "<script>
                alert('중복된 아이디입니다! 다시 작성해주세요.');
                location.replace('../view/signup.php');
            </script>";
    }
} else {
    echo "전달 받은 값이 없습니다!";
}
?>