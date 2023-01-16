<?php 
    /***
     * @brief 게시글 조회 php
     */
    session_start();
    
    require_once __DIR__ . '/../AutoLoad.php';
    
    $auth = new Cmskorea_Board_Auth();
    $board = new Cmskorea_Board_Board();
    
    /* 로그인하지 않았다면 로그인 페이지로 이동 */
    if (!$auth->isLogin()) {
        echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
        echo "<script type=\"text/javascript\">document.location.href='./login.php';</script>";
    }
    
    $memberSession = $auth->getMember();
    
    if (isset($_GET['pk']) && $_GET['pk']) {
        $pk = $_GET['pk'];
    }
    
    /*
     * 없는 게시글 번호이면 리스트로 리다이렉션
     * 있는 게시글 번호이면 내용 전시
     */
    $getRes = $board->getContent($pk);
    if ($getRes == NULL) {
        echo "<script type=\"text/javascript\">alert('존재하지 않는 게시글입니다.');</script>";
        echo "<script type=\"text/javascript\">document.location.href='./boardList.php';</script>";
    }
    
    $fTitle = htmlspecialchars($getRes['title']);
    $fWriter = htmlspecialchars($getRes['writer']);
    $fContent = htmlspecialchars($getRes['content']);
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
                    <?php echo $fTitle; ?>
                    <div class="info">
                        <small class="space writer-info">작성자 &emsp; &emsp; : <?php echo $fWriter; ?></small>
                    </div>
            </div>
            <div class="text-box">
                <?php echo nl2br($fContent); ?>
            </div>
            <div class="file-info">
                 <?php 
                    $fileArrays = $board->getFiles($pk);
                    for ($i = 0; $i < count($fileArrays); $i++) {
                        echo "<li><a class='file-link' href='../process/fileDownload.php?pk={$pk}&filename={$fileArrays[$i]['filename']}'>{$fileArrays[$i]['filename']}</a></li>";
                    }
                 ?>
            </div>
            <div class="time-info">
                 <div class="line">
                     <div class="time-title">등록시간 &emsp; &nbsp; &nbsp; &nbsp; :&nbsp;&nbsp;</div><?php echo $getRes['insertTime']; ?>
                 </div>
                 <div class="line">
                     <div class="time-title">마지막 수정시간 :&nbsp; </div> <?php echo $getRes['updateTime']; ?>
                 </div>
            </div>
            <div class="write-button">
                <input type="submit" class="submit-btn" onclick="location.href='./editBoard.php?pk=<?php ?>';" value="수 &emsp; 정">
                <input type="button" class="cancle-btn" onclick="location.href='./boardList.php';" value="닫 &emsp; 기">
            </div>
        </div>
        <!-- 게시글 내용 조회 끝 -->
    </body>
</html>