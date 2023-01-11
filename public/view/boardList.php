<?php 
    require_once __DIR__ . '/../AutoLoad.php';
    
    session_start();
    
    $auth = new Cmskorea_Board_Auth();
    
    if (!$auth->isLogin()) {
        echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
        echo "<script type=\"text/javascript\">document.location.href='./login.php';</script>";
    }
    $memberSession = $auth->getMember();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="../css/bootstrap/js/bootstrap.js" type="javascript"></script>
    <title>게시글 리스트</title>
</head>
<body>
    <?php include_once __DIR__ . '/commonHeader.php';?>
</body>
</html>