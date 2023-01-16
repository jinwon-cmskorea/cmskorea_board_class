<?php
session_start();
require_once __DIR__ . '/../AutoLoad.php';

//세션을 불러오기 위한 인스턴스 생성
$auth = new Cmskorea_Board_Auth();
//게시글 정보를 불러오기 위한 인스턴스 생성
$board = new Cmskorea_Board_Board();

//로그인한 유저가 접근한 경우 로그인 페이지로 리다이렉션
if (!$auth->isLogin()) {
    echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
    echo "<script type=\"text/javascript\">document.location.href='./login.php';</script>";
}

$memberSession = $auth->getMember();

if (isset($_GET['pk']) && $_GET['pk']) {
    $pk = $_GET['pk'];
}

$getRes = $board->getContent($pk);
if ($getRes == NULL) {
    echo "<script type=\"text/javascript\">alert('존재하지 않는 게시글입니다.');</script>";
    echo "<script type=\"text/javascript\">document.location.href='./boardList.php';</script>";
}

$fTitle = htmlspecialchars($getRes['title']);
$fWriter = htmlspecialchars($getRes['writer']);
$fContent = htmlspecialchars($getRes['content']);
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
    <title>게시글 수정</title>
    </head>
<body>
    <?php include_once __DIR__ . '/commonHeader.php';?>
    <!-- 상단 설명 -->
    <div class="col-sm-12">
        <div class="list-title">
            <strong style="color: rgb(89, 89, 89);">씨엠에스코리아 게시판</strong>
            <small class="small-ele">- 수정 -</small>
        </div>
        <div class="col-sm-12 list-descript2">
            게시판 글을 수정합니다.
        </div>
    </div>
    <!-- 상단 설명 끝 -->
    <!-- 게시글 내용 작성 -->
    <div class="col-sm-10 col-sm-offset-1 list-body">
        <form class="form-horizontal" enctype="multipart/form-data" action="../process/writeBoardOk.php" method="post">
            <input type="hidden" name="memberPk" value="<?php echo $row['pk']; ?>">
            <div class="form-group">
                 <label for="inputTitle" class="col-sm-1 control-label-center">제   목</label>
                <div class="col-sm-11">
                    <input type="text" class="myForm-control2 space-form" id="inputTitle" name="title" value="<?php echo $fTitle; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputContent" class="col-sm-1 control-label-center">내   용</label>
                <div class="col-sm-11">
                    <textarea class="myForm-control2-textarea space-form" rows="10" id="inputContent" name="content" required><?php echo nl2br($fContent); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="inputWriter" class="col-sm-1 control-label-center">작성자</label>
                <div class="col-sm-3">
                    <input type="text" class="myForm-control2 space-form input-wrier" id="inputWriter" name="writer" pattern="[가-힣A-Za-z0-9]+" title="한글, 영문, 숫자 입력가능합니다." value="<?php echo $fWriter; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="inputFile1" class="col-sm-1 control-label-center input-file" style="font-size: 12px;">파일업로드</label>
                <div class="col-sm-3">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" class="myForm-control2 space-form" id="inputFile1" name="inputFile1">
                </div>
            </div>
            <div class="form-group">
                <label for="inputFile2" class="col-sm-1 control-label-center input-file" style="font-size: 12px;">파일업로드</label>
                <div class="col-sm-3">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" class="myForm-control2 space-form" id="inputFile2" name="inputFile2">
                </div>
            </div>
            <div class="time-info2">
                 <div class="line">
                     <div class="time-title">마지막 수정시간 : </div> 
                     <div class="update-time"><?php echo $getRes['updateTime']; ?></div>
                     <div class="write-button">
                         <input type="submit" class="submit-btn" value="수 &emsp; 정">
                         <input type="button" class="cancle-btn" onclick="location.href='./boardList.php';" value="취 &emsp; 소">
                     </div>
                 </div>
            </div>
        <form>
    </div>
    <!-- 게시글 내용 작성 끝 -->
</body>
</html>