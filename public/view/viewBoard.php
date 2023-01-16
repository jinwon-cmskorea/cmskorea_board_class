<?php 
    /***
     * @brief 게시글 조회 php
     */
    session_start();
    
    require_once __DIR__ . '/../AutoLoad.php';
    
    
    $auth = new Cmskorea_Board_Auth();
    
    /* 로그인하지 않았다면 로그인 페이지로 이동 */
    if (!$auth->isLogin()) {
        echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
        echo "<script type=\"text/javascript\">document.location.href='./login.php';</script>";
    }
    
    $memberSession = $auth->getMember();
    
    if (isset($_GET['pk']) && $_GET['pk']) {
        $pk = $_GET['pk'];
    }
    
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
        <script src="../css/bootstrap/js/bootstrap.js" type="text/javascript"></script>
        <title>게시글 조회</title>
        <script type="text/javascript">
        
        </script>
    </head>
    <body>
        <?php include_once __DIR__ . '/commonHeader.php';?>
        <!-- 상단 설명 -->
        <div class="col-sm-12">
            <div class="list-title">
                <strong>씨엠에스코리아 게시판</strong>
                <small class="small-ele">- 조회 -</small>
            </div>
            <div class="col-sm-12 list-descript">
                게시판 글을 조회합니다.
            </div>
        </div>
        <!-- 상단 설명 끝 -->
        <!-- 게시글 조회 작성 -->
        <div class="view-box">
            <div class="page-header-custom">
                    <?php ?>
                    <div class="info">
                        <small class="space writer-info"><?php ?></small>
                        <small class="space"><?php ?></small>
                    </div>
            </div>
            <div class="col-sm-12 text-box">
                <?php  ?>
            </div>
            <div class="col-sm-12">
                <input type="submit" class="btn btn-warning col-sm-6 write-btn-style" onclick="location.href='./editBoard.php?no=<?php echo $row['no']; ?>';" value="수   정">
                <input type="button" class="btn list-btn col-sm-6 write-btn-style" onclick="location.href='./boardList.php';" value="리스트">
            </div>
        </div>
        <!-- 게시글 내용 조회 끝 -->
    </body>
</html>