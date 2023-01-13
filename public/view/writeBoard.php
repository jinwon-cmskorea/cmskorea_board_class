<?php 
    session_start();
    require_once __DIR__ . '/../AutoLoad.php';
    
    //세션을 불러오기 위한 인스턴스 및 메소드
    $auth = new Cmskorea_Board_Auth();
    $memberSession = $auth->getMember();
    
    
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="../css/bootstrap/js/bootstrap.js" type="javascript"></script>
    <title>게시글 작성</title>
    </head>
<body>
    <?php include_once __DIR__ . '/commonHeader.php';?>
    <!-- 상단 설명 -->
    <div class="col-sm-12">
        <div class="list-title">
            <strong style="color: rgb(89, 89, 89);">씨엠에스코리아 게시판</strong>
            <small class="small-ele">- 작성 -</small>
        </div>
        <div class="col-sm-12 list-descript2">
            게시판 글을 작성합니다.
        </div>
    </div>
    <!-- 상단 설명 끝 -->
    <!-- 게시글 내용 작성 -->
    <div class="col-sm-10 col-sm-offset-1 list-body">
        <form class="form-horizontal" action="../process/writeBoardOk.php" method="post">
            <div class="form-group">
                 <label for="inputTitle" class="col-sm-1 control-label-center">제   목</label>
                <div class="col-sm-11">
                    <input type="text" class="form-control space-form" id="inputTitle" name="title" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputContent" class="col-sm-1 control-label-center">내   용</label>
                <div class="col-sm-11">
                    <textarea class="form-control space-form" rows="10" id="inputContent" name="content" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputWriter" class="col-sm-1 control-label-center">작성자</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control space-form" id="inputWriter" name="writer" pattern="[가-힣]+" title="한글 이름만 가능합니다." required>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn bg-primary col-sm-6 write-btn-style" value="등   록">
                <input type="button" class="btn col-sm-6 cancle-btn write-btn-style" onclick="location.href='./boardList.php';" value="취소">
            </div>
        <form>
    </div>
    <!-- 게시글 내용 작성 끝 -->
</body>
</html>