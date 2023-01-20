<?php 
/*
 * @brief 회원가입 동작을 수행하는 페이지 
 */
require_once __DIR__ . '/../AutoLoad.php';

//member 인스턴스 생성
$member = new Cmskorea_Board_Member(DBHOST, USERNAME, USERPW, DBNAME);

//form 으로부터 받아온 값을 이용해 배열 생성
$signupData = array(
    'id'        => $_POST['userId'],
    'pw'        => $_POST['userPw'],
    'name'      => $_POST['userName'],
    'telNumber' => $_POST['userPhone']
);

/*
 * 회원가입 작업, 문제가 발생하면 catch문으로 넘어가서 문제 출력
 * 정상적으로 수행되었다면 성공 메세지 출력 후 로그인 페이지로 이동
 */
try {
    $registRes = $member->registMember($signupData);
    if (is_object($registRes)) {
        echo "<script type=\"text/javascript\">alert('회원가입이 완료되었습니다.')</script>";
        echo "<script type=\"text/javascript\">location.href='../view/login.php'</script>";
    }
} catch (Exception $e) {
    echo "<script type=\"text/javascript\">alert('{$e->getMessage()}')</script>";
    echo "<script type=\"text/javascript\">history.back(-1)</script>";
}