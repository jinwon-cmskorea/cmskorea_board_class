<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="../../js/jQuery/jquery-3.6.3.min.js"></script>
        <link href="../../css/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../../css/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../../css/main.css" type="text/css">
        <title>조회 페이지</title>
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
                    <span class="text-primary text-opacity-75 pagedescription">- 조회 -</span>
                </div>
                <div class="border rounded border-dark-subtle align-self-center descriptionlinebox">
                    <p>게시판 글을 조회합니다.</p>
                </div>
                <div class="my-5">
                    <div>
                        <div class="d-flex justify-content-between">
                            <div class="fs-3" id="boardViewTitle"></div>
                            <span  class="align-self-center" id="boardViewWriter"></span>
                        </div>
                        <div class="contentbox p-3">
                        	<p id="boardViewContent"></p>
                        </div>
                    </div>
                    <div class="mt-3" style="border-bottom: 1px dashed lightgray">
                        <ul>
                            <li>등록된 파일1</li>
                            <li>등록된 파일2</li>
                        </ul>
                    </div>
                    <div class="m-3">
                        <div><span id="boardViewInsertTime"></span></div>
                        <div><span id="boardViewUpdateTime"></span></div>
                    </div>
                    <div class="mx-5 mt-4 row">
                        <button class="btn btn-primary bg-warning border-warning col rounded-0 mx-1" id="postEdit">수 정</button>
                        <button class="col mx-1" style="border: solid 1px lightgray;" id="backList">리스트</button>
                    </div>
                </div>
            </div>
        </div>
    <script>
        $(document).ready(function () {
            const viewPk = location.href.split('?')[1];
            //게시글 조회
            function setViewData(){
                $.ajax ({
                    url : '../../process/boardcheck.php',
                    type : 'POST',
                    dataType : 'JSON',
                    data : {call_name:'view_post', viewPk:viewPk},
                    error : function(jqXHR, textStatus, errorThrown){
                            console.log("실패");
                            alert("게시글 조회에 실패했습니다. ajax 실패 원인 : " + textStatus);
                    }, success : function(result){
                        if (!(result.hasOwnProperty('errorMessage'))) {
                            $("#boardViewTitle").html(result['title']);
                            $("#boardViewWriter").html("작성자 : " + result['writer']);
                            $("#boardViewInsertTime").html("등록시간 : " + result['insertTime']);
                            $("#boardViewUpdateTime").html("마지막 수정시간 : " + result['updateTime']);
                            $("#boardViewContent").html(result['content']);
                        } else {
                            alert("게시글 조회에 실패했습니다! 리스트 화면으로 돌아갑니다." + result['errorMessage']);
                            location.href = "boardlist.php"; 
                        }
                    }
                });
            }
            
            //메인함수
            setViewData();
            
            //수정하기
            $(document).on('click', '#postEdit',function() {
               location.href = "boardedit.php?" + viewPk;
            });
            
            //취소하기
            $(document).on('click', '#backList',function() {
               location.href = "boardlist.php"; 
            });
        });
    </script>
    </body>
</html>