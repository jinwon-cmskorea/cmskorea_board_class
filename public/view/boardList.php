<?php 
    require_once __DIR__ . '/../AutoLoad.php';
    require_once __DIR__ . '/../../configs/dbConfig.php';
    
    session_start();
    
    //인스턴스 생성
    $auth = new Cmskorea_Board_Auth();
    $board = new Cmskorea_Board_Board();
    
    //로그인하지 않은 유저가 접근하면 로그인 페이지로 리다이렉션
    if (!$auth->isLogin()) {
        echo "<script type=\"text/javascript\">alert('먼저 로그인을 진행해주세요.');</script>";
        echo "<script type=\"text/javascript\">document.location.href='./login.php';</script>";
    }
    
    //레코드 갯수를 가져오기 위해 db 연결
    $connect = mysqli_connect(DBHOST, USERNAME, USERPW, DBNAME);
    if (!$connect) {
        die("DB 접속중 문제가 발생했습니다. : ".mysqli_connect_error());
    }
    
    //세션을 불러오기 위한 코드
    $memberSession = $auth->getMember();
    
    /*
     * 페이징을 위한 코드
     * 1. 현재 페이지 저장, 레코드 갯수 구하기
     */
    if (isset($_GET['page']) && $_GET['page']) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    
    // 2. 전체 레코드 갯수 구하기
    $sql = "SELECT COUNT(pk) AS count FROM board";
    $res = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($res);
    $recordCnt = $row['count'];
    
    // 3. 페이징 관련 변수 선언
    $totalPageCnt = ceil($recordCnt / 10);//전체 페이지 갯수
    $totalPageBlock = ceil($totalPageCnt / 10);//페이지 블록 갯수(ex: 1~10 : 1번블록, 11~20 : 2번 블록..)
    $nowPageBlock = ceil($page / 10);//현재 페이지가 속해있는 블록(ex: 7번 페이지는 1번 블록에 속함)
    $start = (($page - 1) * 10) + 1;//가져올 레코드 시작 번호
    $start -= 1;//배열 0번째부터 가져와야 하므로 1을 빼줌
    $startPage = (($nowPageBlock - 1) * 10) + 1;//페이지 시작 번호
    
    //정렬, 검색, 페이징 여부를 확인하기 위한 배열 선언
    $conditionCheck = array('category', 'search', 'fieldName', 'order');
    
    //상태 값이 들어있으면 배열에 삽입
    $conditions = array();
    foreach ($conditionCheck as $condition) {
        if (isset($_GET[$condition]) && $_GET[$condition]) {
            $conditions[$condition] = $_GET[$condition];
        }
    }
    $conditions['start'] = $start;
    //조건에 따라 게시글을 불러오는 코드
    $posts = $board->getContents($conditions);
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
        <!-- 페이징 버튼 -->
        <nav class="text-center">
            <ul class="pagination">
                <?php
                //첫 페이지로 이동
                if ($page == 1) {
                    echo "<li class='disabled'><a href=\"#\">First</a></li>";
                } else {
                    echo "<li><a href=\"boardList.php?page=1\">First</a></li>";
                }
                
                //시작 페이지를 기준으로 출력해야할 페이지 갯수 구하기
                $endPage = ($startPage + 10) <= $totalPageCnt ? ($startPage + 10) - 1 : $totalPageCnt;
                
                //이전 페이지 블록으로 이동하기
                $prevPage = ($startPage - 10) + 9;
                if ($prevPage >= 1) {
                    echo "<li><a href=\"boardList.php?page={$prevPage}\">&lt</a></li>";
                } else {
                    echo "<li class='disabled'><a href=\"#\">&lt</a></li>";
                }
                
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($page == $i) {
                        echo "<li class='active'><a href=\"boardList.php?page={$i}\">$i</a></li>";
                    } else {
                        echo "<li><a href=\"boardList.php?page={$i}\">$i</a></li>";
                    }
                }
                
                //다음 페이지 블록으로 이동하기
                $nextBlock = $startPage + 10;
                if ($nextBlock <= $totalPageCnt) {
                    echo "<li><a href=\"boardList.php?page={$nextBlock}\">&gt</a></li>";
                } else {
                    echo "<li class='disabled'><a href=\"#\">&gt</a></li>";
                }
                
                //마지막 페이지로 이동
                if ($page < $totalPageCnt) {
                    echo "<li><a href=\"boardList.php?page={$totalPageCnt}\">Last</a></li>";
                } else {
                    echo "<li class='disabled'><a href=\"#\">Last</a></li>";
                }
                ?>
            </ul>
        </nav>
        <!-- 페이징 버튼 끝 -->
    </div>
</body>
</html>