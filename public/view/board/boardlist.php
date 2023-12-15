<?php
require_once './../../process/autoload.php';

$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
//페이지 번호
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
//검색 키워드 받기
if (isset($_GET['searchTag']) && $_GET['searchTag']) {
    $searchTag = $_GET['searchTag'];
} else {
    $searchTag = null;
}
if (isset($_GET['searchInput']) && $_GET['searchInput']) {
    $searchInput = $_GET['searchInput'];
} else {
    $searchInput = null;
}
//정렬 받기
if (isset($_GET['orderName']) && $_GET['orderName']) {
    $orderName = $_GET['orderName'];
} else {
    $orderName = null;
}
if (isset($_GET['sort']) && $_GET['sort']) {
    $sort = $_GET['sort'];
} else {
    $sort = null;
}
//데이터개수
$listNum = 10;
//페이지수
$pageNum = 5;

$totaPage = 1;
//검색 결과 표시
$searchAllCount = 0;
$searchCount = 0;
//검색, 정렬 데이터 배열에 저장
$selectArr = array();
if (!is_null($searchTag) && !is_null($searchInput)) {
    $selectArr["searchTag"] = $searchTag;
    $selectArr["searchInput"] = $searchInput;
}
if (!is_null($orderName) && !is_null($sort)) {
    $selectArr["orderName"] = $orderName;
    $selectArr["sort"] = $sort;
}
$selectArr["start_list"] = $page;
//쿼리 사용 데이터 가져오기
try {
    if (!is_null($searchTag) && !is_null($searchInput)) {
        //검색 결과 전체 페이지 수
        $pageCountArr = array();
        $searchAllCount = mysqli_num_rows($boardDBclass->getContents($pageCountArr));
        $pageCountArr["searchTag"] = $searchTag;
        $pageCountArr["searchInput"] = $searchInput;
        $searchCount = mysqli_num_rows($boardDBclass->getContents($pageCountArr));
        $totaPage = ceil($searchCount / $listNum);
    } else {
        //전체 페이지 수
        $pageCountArr = array();
        $searchAllCount = mysqli_num_rows($boardDBclass->getContents($pageCountArr));
        $totaPage = ceil($searchAllCount / $listNum);
    }
} catch (Exception $e) {
    echo "<script>
             console.log(\"" . $e->getMessage() . "\");
        </script>";
}
//전체 블럭 수
$totalBlock = ceil($totaPage / $pageNum);
//현재 페이지 번호
$nowBlock = ceil($page / $pageNum);
//블럭 당 시작 페이지 번호
$startPageNum = ($nowBlock - 1) * $pageNum + 1;
// 데이터가 0개인 경우
if ($startPageNum <= 0) {
    $startPageNum = 1;
};
//블럭 당 마지막 페이지 번호
$endPageNum = $nowBlock *  $pageNum ;
// 마지막 번호가 전체 페이지 수를 넘지 않도록
if ($endPageNum > $totaPage) {
    $endPageNum = $totaPage;
};
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="../../js/jQuery/jquery-3.6.3.min.js"></script>
        <link href="../../css/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../../css/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../../css/main.css" type="text/css">
        <title>리스트 페이지</title>
    </head>
    <body>
        <div class="container border border-secondary listcontainer">
            <div class="header-include"></div>
            <?php
            include 'boardheader.php';
            ?>
            <div style="margin: 15px;">
                <div class=" text-start" style="margin-bottom: 15px;">
                    <span class="fs-5 pagetitle">씨엠에스코리아 게시판</span>
                    <span class="text-primary text-opacity-75 pagedescription">- 리스트 -</span>
                </div>
                <div class="border rounded  border-dark-subtle align-self-center descriptionbox">
                    <p>등록 된 게시글을 조회하는 페이지입니다.<br>
                    등록 된 글은 조회, 수정 및 삭제 할 수 있습니다.</p>
                </div>
                <div>
                    <div>
                        <div class="row justify-content-between" style="height: 30px; margin-bottom: 10px;">
                            <form class="col-6" method="GET" action="boardlist.php">
                                <select class="text-white text-center" name="searchTag" id="searchSelectBox">
                                    <option value="writer">작성자</option>
                                    <option value="title">제목</option>
                                    <option value="insertTime">작성일자</option>
                                </select>
                                <input  type="text" style="border: 1px solid lightgray;" id="searchBar" name="searchInput" placeholder="검색어를 입력해주세요." value="<?php echo $searchInput; ?>">
                                <input type="hidden" name="orderName" value="<?php echo $orderName;?>">
                                <input type="hidden" name="sort" value="<?php echo $sort;?>">
                                <button class="btn btn-primary me-4" id="searchButton">검색</button>
                                <?php
                                if(!is_null($searchTag) && !is_null($searchInput)) {
                                ?> 
                                    <span><?php printf("%04d", $searchCount);?></span> 
                                    <span> / </span> 
                                    <span><?php printf("%04d", $searchAllCount);?> 건</span> 
                                <?php
                                }
                                ?>
                            </form>
                            <div id="alertBox" class="col-3"></div>
                            <button class="btn btn-primary col-1" style="height:38px" id="boardWrite">작성</button>
                        </div>
                        <div>
                            <table class="table" style="border-top: 1px solid lightgray;" id="boardTable">
                                <thead>
                                    <tr class="text-center">
                                        <th class="col-1 cursorPointer" id="boardPk" value="pk" onClick="sortcheck('pk')">번호</th>
                                        <th class="col-5 text-center cursorPointer" id="boardTitle" value="title" onClick="sortcheck('title')">제목</th>
                                        <th class="col-2 cursorPointer" id="boardWriter" value="writer" onClick="sortcheck('writer')">작성자</th>
                                        <th class="col-1 cursorPointer" id="boardInsertTime" value="insertTime" onClick="sortcheck('insertTime')">작성일자</th>
                                        <th class="col-2" id="nosort" value="nosort">작업</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                //테이블 출력
                                try{
                                    $dbBoardList = $boardDBclass->getContents($selectArr);
                                    //query 결과 검사
                                    if (is_string($dbBoardList)) {
                                        throw new Exception($dbBoardList);
                                    }
                                    if (!is_null($searchTag) && !is_null($searchInput) && $totaPage == 0) {
                                    ?>
                                        <tr class='align-middle' >
                                            <td class='align-middle text-center fs-3 fw-bold py-4' colspan='4'>검색결과가 존재하지 않습니다!</td>
                                            <td class='align-middle text-center fs-5 fw-bold py-4'><a class='link-info' href='boardlist.php'>돌아가기</a></td>
                                        </tr>
                                    <?php 
                                    } else {
                                        foreach ($dbBoardList as $value) { 
                                    ?>  
                                        <tr class='align-middle text-center' >
                                            <th scope='row'><?php printf("%04d", $value["pk"]); ?></th>
                                            <td class="text-start"><?php echo $value["title"]; ?></td>
                                            <td><?php echo $value["writer"]; ?></td>
                                            <td><?php echo substr($value["insertTime"],0,10); ?></td>
                                            <td><button type='button' class='btn btn-warning text-white viewButton'>조회</button>
                                            <button type='button' class='btn btn-danger deleteButton ms-1'>삭제</button></td>
                                        </tr>
                                  <?php
                                        }
                                  ?>
                                </tbody>
                                    </table>
                                    </div>
                                    <nav aria-label="Page navigation example" id="pagingnav">
                                        <ul class="pagination justify-content-center" id="pagination">
                                            <li class='page-item'><a class='page-link' href='boardlist.php?page=1&searchTag=<?php echo $searchTag; ?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'>First</a></li>
                                            <?php
                                            /* pager : 페이지 번호 출력 */
                                             if ($page <= 1) {
                                             ?>
                                             <li class='page-item'><a class='page-link' href='boardlist.php?page=1&searchTag=<?php echo $searchTag; ?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'>&lt</a></li>
                                             <?php
                                             } else {
                                             ?>
                                               <li class='page-item'><a class='page-link' href='boardlist.php?page=<?php echo ($page-1); ?>&searchTag=<?php echo $searchTag; ?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'>&lt</a></li>
                                            <?php
                                            };
                                            for ($printPage = $startPageNum; $printPage <= $endPageNum; $printPage++) {
                                                if ($page == $printPage) {
                                                ?>
                                                    <li class='page-item'><a class='page-link  bg-info-subtle' href='boardlist.php?page=<?php echo $printPage; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'><?php echo $printPage; ?></a></li>
                                                <?php
                                                } else {
                                                ?>
                                                    <li class='page-item'><a class='page-link' href='boardlist.php?page=<?php echo $printPage; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'><?php echo $printPage; ?></a></li>
                                            <?php
                                                }
                                            };
                                            
                                            if ($page >= $totaPage) {
                                            ?>
                                                <li class='page-item'><a class='page-link' href='boardlist.php?page=<?php echo $totaPage; ?>&searchTag=<?php echo $searchTag; ?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'>&gt</a></li>
                                            <?php
                                            } else{ 
                                            ?>
                                                <li class='page-item'><a class='page-link' href='boardlist.php?page=<?php echo ($page+1); ?>&searchTag=<?php echo $searchTag; ?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'>&gt</a></li>
                                            <?php
                                            };
                                            ?>
                                             <li class='page-item'><a class='page-link' href='boardlist.php?page=<?php echo $totaPage; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput; ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort; ?>'>Last</a></li>
                                      </ul>
                                    </nav>
                                    <?php
                                    }
                                } catch (Exception $e) {
                                    ?>
                                    <tr><td class='align-middle text-center fs-3 fw-bold py-4' colspan='5'>게시글 리스트를 불러오기 실패했습니다!</td></tr>
                                    <tr><td class='align-middle text-center py-2' colspan='5'><?php echo $e->getMessage(); ?></td></tr>
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                </div>
            </div>
        <script type="text/javascript" src="../../js/appendAlert.js"></script>
        <script type="text/javascript">
            //목록 정렬
            function sortcheck(ordername){
                $('table > thead').find('th').each(function(inx, th) {
                    th.innerHTML = th.innerHTML.replace(/[▼▲]/g, '');
                });
                <?php 
                if ($sort === 'asc' || is_null($sort)) {
                    $sort = 'desc';
                } else {
                    $sort = 'asc';
                }
                if (!is_null($searchTag) && !is_null($searchInput)) {
                ?>
                location.href = 'boardlist.php?page=<?php echo $page; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput; ?>&orderName=' + ordername + '&sort=<?php echo $sort; ?>';
                <?php
                } else {
                ?>
                location.href = 'boardlist.php?page=<?php echo $page; ?>&orderName=' + ordername + '&sort=<?php echo $sort; ?>';
                <?php
                }
                ?>
            }
            <?php 
            if (!is_null($orderName) && !is_null($sort)) {
                if ($sort === 'asc') {
                ?>
                $("th[value=" + '<?php echo $orderName; ?>' + "]").append('▼');
                <?php
                } else {
                ?>
                $("th[value=" + '<?php echo $orderName; ?>' + "]").append('▲');
                <?php
                }
            }
            ?>
            $(document).ready(function () {
                //게시글 조회
                $(document).on('click', 'body div.container .viewButton', function() {
                    var thisRow = $(this).closest('tr'); 
                    var viewPk = parseInt(thisRow.find('th').text());
                    
                    location.href = "boardview.php?post="+viewPk; 
                });
                //게시글 삭제 경고창 띄우기
                $(document).on('click', 'body div.container .deleteButton', function() {
                    $("#alertBox").empty();
                    var thisRow = "";
                    thisRow = $(this).closest('tr'); 
                    var deletePk = parseInt(thisRow.find('th').text());
                    appendDeleteAlert("&#10071;정말로 " + deletePk + "번째 게시글을 삭제하시겠습니까?", 'alertBox');
                    $("#deletewrapperpk").attr("value",deletePk);
                });
                //게시글 삭제
                $(document).on('click', 'body div.container .DeleteCompleteClose', function() {
                    deletePk = $("#deletewrapperpk").attr("value");
                    $.ajax({
                        url : '../../process/boardcheck.php',
                        type : 'POST',
                        dataType : 'text',
                        data : {call_name:'delete_post', deletePk:deletePk},
                        error : function(jqXHR, textStatus, errorThrown){
                           alert("게시글 삭제 실패했습니다. ajax 실패 원인 : " + textStatus);
                        }, success : function(result){
                            if (result) {
                                $("#alertBox").empty();
                                location.reload();
                            } else {
                                alert("게시글 삭제 실패했습니다.");
                            }
                        }
                    });
                });
                //게시글 삭제 경고창 취소
                $(document).on('click', 'body div.container .DeleteClose', function() {
                    $("#alertBox").empty();
                    location.reload();
                });
                //게시글 작성
                $('#boardWrite').click(function() {
                   location.href = 'boardwrite.php'; 
                });
            });
        </script>
    </body>
</html>