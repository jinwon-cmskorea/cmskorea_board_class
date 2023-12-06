<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/configs/dbconfigs.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/cmskorea_board_class/library/Cmskorea/Board/Board.php';
    if (!session_id()) {
        session_start();
    }
    //페이지 번호
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    //검색 키워드 받기
    if (isset($_GET['searchTag']) && !empty($_GET['searchTag'])) {
        $searchTag = $_GET['searchTag'];
    } else {
        $searchTag = null;
    }
    if (isset($_GET['searchInput']) && !empty($_GET['searchInput'])) {
        $searchInput = $_GET['searchInput'];
    } else {
        $searchInput = null;
    }
    //정렬 받기
    if (isset($_GET['orderName']) && !empty($_GET['orderName'])) {
        $orderName = $_GET['orderName'];
    } else {
        $orderName = null;
    }
    if (isset($_GET['sort']) && !empty($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = null;
    }
    //데이터개수
    $list_num = 10;
    //페이지수
    $page_num = 10;
    
    //검색, 정렬 데이터 배열에 저장
    $selectarr = array();
    if (isset($searchInput)) {
        $selectarr["searchTag"] = $searchTag;
        $selectarr["searchInput"] = $searchInput;
    }
    if (isset($orderName)) {
        $selectarr["orderName"] = $orderName;
        $selectarr["sort"] = $sort;
    }
    $selectarr["start_list"] = $page;
    $selectarr["last_list"] = $list_num;
    $boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
    //쿼리 사용 데이터 가져오기
    $dblist = $boardDBclass->getContents($selectarr);
    if (isset($searchTag) && isset($searchInput)) {
        //검색 결과 전체 페이지 수
        $selectarr = array();
        $selectarr["searchTag"] = $searchTag;
        $selectarr["searchInput"] = $searchInput;
        $total_page = ceil(mysqli_num_rows($boardDBclass->getContents($selectarr)) / $list_num);
    } else {
        //전체 페이지 수
        $selectarr = array();
        $total_page = ceil(mysqli_num_rows($boardDBclass->getContents($selectarr)) / $list_num);
    }
    unset($selectarr);
    //전체 블럭 수
    $total_block = ceil($total_page / $page_num);
    //현재 페이지 번호
    $now_block = ceil($page / $page_num);
    //블럭 당 시작 페이지 번호
    $s_pageNum = ($now_block - 1) * $page_num + 1;
    // 데이터가 0개인 경우
    if ($s_pageNum <= 0) {
        $s_pageNum = 1;
    };
    //블럭 당 마지막 페이지 번호
    $e_pageNum = $now_block *  $page_num ;
    // 마지막 번호가 전체 페이지 수를 넘지 않도록
    if ($e_pageNum > $total_page) {
        $e_pageNum = $total_page;
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
                                <input  type="text" style="border: 1px solid lightgray;" id="searchBar" name="searchInput" placeholder="검색어를 입력해주세요.">
                                <input type="hidden" name="orderName" value="<?php echo $orderName?>">
                                <input type="hidden" name="sort" value="<?php echo $sort?>">
                                <button class="btn btn-primary" id="searchButton">검색</button>
                            </form>
                            <div id="alertBox" class="col-3"></div>
                            <button class="btn btn-primary col-1" style="height:38px" id="boardWrite">작성</button>
                        </div>
                        <div>
                            <table class="table" style="border-top: 1px solid lightgray;" id="boardTable">
                                <thead>
                                    <tr>
                                        <th class="col-1" id="boardPk" value="pk"  onClick="sortcheck('pk')">번호</th>
                                        <th class="col-6 text-center" id="boardTitle" value="title" onClick="sortcheck('title')">제목</th>
                                        <th class="col-1" id="boardWriter" value="writer" onClick="sortcheck('writer')">작성자</th>
                                        <th class="col-1" id="boardInsertTime" value="insertTime" onClick="sortcheck('insertTime')">작성일자</th>
                                        <th class="col-2" id="nosort" value="nosort">작업</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                //테이블 출력
                                try{
                                    //query 결과 검사
                                    if (is_string($dblist)) {
                                        throw new Exception($dblist);
                                    }
                                    if (isset($searchTag) && isset($searchInput) && $total_page == 0) {
                                        ?><tr class='align-middle' >
                                            <td class='align-middle text-center fs-3 fw-bold py-4' colspan='4'>검색결과가 존재하지 않습니다!</td>
                                            <td class='align-middle text-center fs-5 fw-bold py-4'><a class='link-info' href='boardlist.php'>돌아가기</a></td>
                                        </tr>
                                        <?php 
                                    } else {
                                        foreach ($dblist as $value) { ?>  
                                            <tr class='align-middle' >
                                            <th scope='row'><?php echo $value["pk"];?></th>
                                            <td><?php echo $value["title"];?></td>
                                            <td><?php echo $value["writer"];?></td>
                                            <td><?php echo substr($value["insertTime"],0,10);?></td>
                                            <td><button type='button' class='btn btn-warning text-white viewButton'>조회</button>
                                            <button type='button' class='btn btn-danger deleteButton ms-1'>삭제</button></td>
                                            </tr>
                                    <?php }?>
                                </tbody>
                                    </table>
                                    </div>
                                    <nav aria-label="Page navigation example" id="pagingnav">
                                      <ul class="pagination justify-content-center" id="pagination">
                                          <li class='page-item'><a class='page-link' href='boardlist.php?page=1&searchTag=<?php echo $searchTag; ?>&searchInput=<?php echo $searchInput ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort ?>'>First</a></li>
                                        <?php
                                            /* pager : 페이지 번호 출력 */
                                            for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) {
                                            ?>
                                            <li class='page-item'><a class='page-link' href="boardlist.php?page=<?php echo $print_page; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort ?>"><?php echo $print_page; ?></a></li>
                                            <?php };?>
                                             <li class='page-item'><a class='page-link' href='boardlist.php?page=<?php echo $e_pageNum; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput ?>&orderName=<?php echo $orderName; ?>&sort=<?php echo $sort ?>'>Last</a></li>
                                      </ul>
                                    </nav>
                                    <?php }
                                } catch (Exception $e) {
                                    $m = $e->getMessage()?>
                                    <tr><td class='align-middle text-center fs-3 fw-bold py-4' colspan='5'>게시글 리스트를 불러오기 실패했습니다!</td></tr>
                                    <tr><td class='align-middle text-center py-2' colspan='5'>오류내용 : " + <?php echo $m  ?> + "</td></tr>
                                <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        <script type="text/javascript">
            //정렬
            function sortcheck(ordername){
                $('table > thead').find('th').each(function(inx, th) {
                    th.innerHTML = th.innerHTML.replace(/[▼▲]/g, '');
                });
                <?php 
                if ($sort === 'asc' || !(isset($sort))) {
                    $sort = 'desc';
                } else {
                    $sort = 'asc';
                }
                if (isset($searchTag) && isset($searchInput)) {
                    ?>location.href = 'boardlist.php?page=<?php echo $page; ?>&searchTag=<?php echo $searchTag;?>&searchInput=<?php echo $searchInput ?>&orderName=' + ordername + '&sort=<?php echo $sort ?>';<?php
                } else {
                    ?>location.href = 'boardlist.php?page=<?php echo $page; ?>&orderName=' + ordername + '&sort=<?php echo $sort ?>';<?php
                }?>
            }
            <?php 
            if (isset($orderName) && isset($sort)) {
                if ($sort === 'asc') {
                    ?>$("th[value=" + '<?php echo $orderName; ?>' + "]").append('▲');<?php
                } else {
                    ?>$("th[value=" + '<?php echo $orderName; ?>' + "]").append('▼');<?php
                }
            }
            ?>
            $(document).ready(function () {
            //삭제 경고창
            const appendDelete = (message, id) => {
                const DeletePlaceholder = document.getElementById(id);
                const Deletewrapper = document.createElement('div')
                Deletewrapper.innerHTML = [
                    `<div class="border border-danger border-2 rounded bg-danger-subtle text-dark p-2 alertDelete" style="position: absolute" id="alertDelete">`,
                    `   <div id="deletewrapperpk">${message}</div>`,
                    '   <button type="button" id="DeleteCompleteClose" class="btn btn-danger DeleteCompleteClose">삭제</button>',
                    '   <button type="button" id="DeleteClose" class="btn btn-secondary DeleteClose">취소</button>',
                    '</div>'
                    ].join('')
                DeletePlaceholder.append(Deletewrapper)
            }
                //게시글 조회
                $(document).on('click', 'body div.container .viewButton', function() {
                    var thisRow = $(this).closest('tr'); 
                    var viewPk = parseInt(thisRow.find('th').text());
                    
                    location.href = "boardview.php?"+viewPk; 
                });
                //게시글 삭제
                $(document).on('click', 'body div.container .deleteButton', function() {
                    $("#alertBox").empty();
                    var thisRow = "";
                    thisRow = $(this).closest('tr'); 
                    var deletePk = parseInt(thisRow.find('th').text());
                    appendDelete("&#10071;정말로 " + deletePk + "번째 게시글을 삭제하시겠습니까?", 'alertBox');
                    $("#deletewrapperpk").attr("value",deletePk);
                });
                $(document).on('click', 'body div.container .DeleteCompleteClose', function() {
                    deletePk = $("#deletewrapperpk").attr("value");
                    $.ajax({
                    url : '../../process/boardcheck.php',
                    type : 'POST',
                    dataType : 'text',
                    data : {call_name:'delete_post', deletePk:deletePk},
                    error : function(e){
                    console.log(e);
                    }, success : function(result){
                        console.log(result);
                        }
                    });
                    $("#alertBox").empty();
                    location.reload();
                });
                $(document).on('click', 'body div.container .DeleteClose', function() {
                    $("#alertBox").empty();
                    location.reload();
                });
                //게시글 작성
                $(document).on('click', '#boardWrite',function(){
                   location.href = 'boardwrite.php'; 
                });
            });
        </script>
    </body>
</html>