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
    <div class="col-sm-12">
        <div class="list-title">
            <strong style="color: rgb(89, 89, 89);">씨엠에스코리아 게시판</strong>
            <small style="color: rgb(132, 151, 176);">- 리스트 -</small>
        </div>
        <div class="col-sm-12 list-descript">
            등록 된 게시글을 조회하는 페이지입니다.<br>
            등록 된 글은 조회, 수정, 삭제할 수 있습니다.
        </div>
    </div>
    <!-- 상단 끝 -->
    <!-- 검색, 작성, 게시글 리스트, 페이징 등  -->
    <div class="col-sm-12 list-body">
        <div class="board-upper">
            <form action="./boardList.php" method="get">
                <input type="hidden" name="page" value="1" />
                <select class="selectbox" id="category" name="category">
                    <option value="writer" <?php //echo (isset($category) && $category == 'writer') ? 'selected' : ''; ?>>작성자</option>
                    <option value="title" <?php //echo (isset($category) && $category == 'title') ? 'selected' : ''; ?>>제목</option>
                    <option value="insertTime" <?php //echo (isset($category) && $category == 'insertTime') ? 'selected' : ''; ?>>작성일자</option>
                </select>
                <input class="s-input" type="text" name="search" autocomplete="off" value="<?php //echo (isset($search) && $search) ? $search : ''; ?>">
                <input class="btn s-button" type="submit" value="검색">
            </form>
            <div class="searchCnt"> 00 / 1,000 건</div>
            <div>
                <input class="btn bg-primary write-btn" type="button" onclick="location.href='./writeBoard.php';" value="작    성">
            </div>
        </div>
    </div>
</body>
</html>