<?php 
    require_once __DIR__ . '/../AutoLoad.php';
    
    session_start();
    
    //인스턴스 생성
    $auth = new Cmskorea_Board_Auth();
    $board = new Cmskorea_Board_Board();
    
    //로그인하지 않은 유저가 접근하면 로그인 페이지로 리다이렉션
    if (!$auth->isLogin()) {
        echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
        echo "<script type=\"text/javascript\">document.location.href='./login.php';</script>";
    }
    
    //세션을 불러오기 위한 코드
    $memberSession = $auth->getMember();
    
    //조건에 따라 게시글을 불러오는 코드
    $posts = $board->getContents(array());
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
        <!-- 검색 부분, 작성버튼 끝-->
        <!-- 게시글 리스트 테이블  -->
        <table class="myTable">
            <thead class="add-top-line">
                <tr>
                    <th class="col-sm-1">번호</th>
                    <th class="col-sm-7">제목</th>
                    <th class="col-sm-1">작성자</th>
                    <th class="col-sm-1">작성일자</th>
                    <th class="col-sm-2">작업</th>
                </tr>
            <thead>
            <tbody>
                <?php 
                for ($i = 0; $i < 10; $i++) {
                    $ymd = substr($posts[$i]['insertTime'], 0, 10);
                    //입력된 내용 필터링
                    $escapedTitle = htmlspecialchars($posts[$i]['title']);
                    $escapedWriter = htmlspecialchars($posts[$i]['writer']);
                ?>
                <tr class="add-bottom-line">
                    <td><?php echo $posts[$i]['pk']; ?></td>
                    <td style="text-align: left;"><?php echo $escapedTitle; ?></td>
                    <td><?php echo $escapedWriter; ?></td>
                    <td><?php echo $ymd; ?></td>
                    <td>
                    	<div style="text-align: center;">
                            <input class="btn view-btn" type="button" onclick="location.href='./viewBoard.php?pk=<?php echo $posts[$i]['pk']; ?>';" value="조회">
                            <input class="btn del-btn btn-delete" name="delete-btn" type="button" value="삭제" data-no="<?php echo $posts[$i]['pk']; ?>">
                        </div>
                    </td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
        <!-- 게시글 리스트 테이블 끝 -->
    </div>
</body>
</html>