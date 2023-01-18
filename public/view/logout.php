<?php 
    require_once __DIR__ . '/../AutoLoad.php';
    
    session_start();
    
    //Auth 의 logout 메소드로 세션 제거
    $auth = new Cmskorea_Board_Auth();
    $auth->logout();
    session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 3초 후 이동 시키는 메타 태그 -->
    <meta http-equiv='refresh' content='3; url=../index.php'>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../css/bootstrap/js/bootstrap.js" type="javascript"></script>
    <title>로그아웃</title>
</head>
<body>
    <div class="container center">
        <div class="col-sm-6" style="background-color: rgb(231, 230, 230);">
            <div class="text-center">
                <div class="sep-line">
                   <strong>CMSKOREA Board</strong>
                </div>
            </div>
            <div class="text-center logout-text">
            	로그아웃 되었습니다.
            </div>
            <div class="containerFooter">
                <div class="threeMsg">3초 후 처음 화면으로 이동합니다...</div>
                <input type="button" class="logoutOk-btn" value="확인" onclick="location.href='../index.php'">
            </div>
        </div>
    </div>
</body>
</html>