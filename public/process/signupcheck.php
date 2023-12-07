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
    //데이터 유효성 검사
    if (!((preg_match("/[0-9]/", $_POST['memberId'])) || (preg_match("/[a-z]/i", $_POST['memberId'])))) {
        alert_replace ("데이터 전달에 실패했습니다. 아이디를 영문 또는 숫자가 포함되도록 다시 작성해주세요.", "../view/signup.php");
    } else if (!(preg_match("/[~!@#$%^&*()_+|<>?:{}]/", $_POST['memberPw']))) {
        alert_replace ("데이터 전달에 실패했습니다. 비밀번호는 특수문자 1개 필수입니다. 다시 작성해주세요.", "../view/signup.php");
    } else if (preg_match("/[~!@#$%^&*()_+|<>?:{}]/", $_POST['memberName'])) {
        alert_replace ("데이터 전달에 실패했습니다. 이름을 한글 또는 영문만 있도록 다시 작성해주세요.", "../view/signup.php");
    } else if (!(preg_match("/^(?:(010-\d{4})|(01[1|6|7|8|9]-\d{3,4}))-(\d{4})$/", $_POST['memberTel']))) {
        alert_replace ("데이터 전달에 실패했습니다. 휴대전화번호 형식을 일치하도록 다시 작성해주세요.", "../view/signup.php");
    } else {
        $signupArr = array();
        $signupArr['id'] = $_POST['memberId'];
        $signupArr['pw'] = $_POST['memberPw'];
        $signupArr['name'] = $_POST['memberName'];
        $signupArr['telNumber'] = str_replace('-', '', $_POST['memberTel']);
        
        
        if ($memberDBclass->getMember($signupArr['id'])) {
            alert_replace ("중복된 아이디입니다! 다시 작성해주세요.", "../view/signup.php");
        } else {
            $result = $memberDBclass->registMember($signupArr);
            alert_replace ("회원 가입이 완료되었습니다!", "../view/login.php");
        }
    }
} else {
    alert_replace ("전달받은 값이 없습니다! 다시 작성해주세요.", "../view/signup.php");
}
?>