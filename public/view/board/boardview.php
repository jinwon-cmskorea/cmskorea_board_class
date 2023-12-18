<?php 
require_once './../../process/autoload.php';

$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);

if (isset($_GET['post']) && $_GET['post']) {
    $post = $_GET['post'];
} else {
    $post = 0;
}
//수정 유저 확인, 게시글 조회
try {
    $userData = $authDBclass->getMember();
    $postList = $boardDBclass->getContent($post);
    
    $fileList = $boardDBclass->getFiles($post);
    $replyList = $boardDBclass->getReply($post);
?>
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
        <div class="container border border-secondary">
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
                            <div class="fs-3 ms-2" id="boardViewTitle"><?php echo $postList['title']; ?></div>
                            <span  class="align-self-center fw-bold" id="boardViewWriter">작성자 : <?php echo $postList['writer']; ?></span>
                        </div>
                        <div class="contentbox p-3">
                        	<p id="boardViewContent"><?php echo $postList['content']; ?></p>
                        </div>
                    </div>
                    <?php
                    if ($fileList) {
                    ?>
                    <div class="mt-3" style="border-bottom: 1px dashed lightgray">
                        <ul>
                        <?php 
                        foreach ($fileList as $value) {
                        ?>
                            <li><a class="text-black text-decoration-none previewFile" filePk="<?php echo $value['pk'];?>" href="../../process/fileDownload.php?boardPk=<?php echo $post; ?>&filePk=<?php echo $value['pk'];?>"><?php echo $value['filename'];?></a></li>
                        <?php 
                        }
                        ?>
                        </ul>
                    </div>
                    <?php 
                    }
                    ?>
                    <div class="m-3 d-flex">
                        <div class="fw-bold">
                            <div>등록시간</div>
                            <div>마지막 수정시간</div>
                        </div>
                        <div class="ms-1">
                            <div><span id="boardViewInsertTime">: <?php echo $postList['insertTime']; ?></span></div>
                            <div><span id="boardViewUpdateTime">: <?php echo $postList['updateTime']; ?></span></div>
                        </div>
                    </div>
                    <?php
                    //게시글 수정 권한 확인하기
                    if ($postList['memberPk'] == $userData['pk'] || $userData['id'] === "root") {
                    ?>
                        <div class="mx-5 mt-4 row">
                            <button class="btn btn-primary bg-warning border-warning col rounded-0 mx-1" id="postEdit">수 정</button>
                            <button class="col mx-1" style="border: solid 1px lightgray;" id="backList">리스트</button>
                        </div>
                    <?php
                    } else {
                    ?> 
                        <div class="mx-5 mt-4 row">
                            <button class="col mx-1 btn" style="border: solid 1px lightgray;" id="backList">리스트</button>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="mt-5 m-3">
                    <hr>
                        <div class=" m-3 fw-bold text-secondary-emphasis">게시글 댓글</div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" style="height:80px; overflow :auto;" placeholder="댓글을 입력해주세요." id="boardReplyContent"></textarea>
                            <label for="boardReplyContent">댓글을 입력해주세요.</label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-secondary" type="button" id="boardReplyButton">작성</button>
                        </div>
                        <hr>
                        <?php 
                        if ($replyList) {
                        ?>
                            <div style="height: 200px; overflow: auto">
                        <?php
                            foreach ($replyList as $value) {
                        ?>
                            <div class="border border-secondary-subtle rounded p-2 mb-3">
                                <div class="ms-4 fw-bold">작성자 : <?php echo $value['name']; ?></div>
                                <div class="border rounded p-3 mx-3 my-1" style="height:80px; overflow :auto;"><?php echo $value['content']; ?></div>
                                <div class="text-end">
                                    <span>등록시간 : <?php echo $value['insertTime']; ?></span>
                                    <button value="<?php echo $value['pk']; ?>" class="btn btn-danger btn-sm replyDelete me-3" id="boardReplyDeleteButton">삭제</button>
                                </div>
                            </div>
                        <?php 
                            }
                        ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <script>
        $(document).ready(function () {
            //다운로드 이미지 미리보기
            var xOffset = 80;
            var yOffset = 30;
            <?php
            foreach ($fileList as $value) {
            ?>
                //다운로드 파일명에 마우스 오버
                $(document).on("mouseover", ".previewFile", function(e){
                    var pk = $(this).attr("filePk");
                    if (pk == <?php echo $value['pk'];?>) {
                        $("body").append("<p id='preview'><img src='data:image/png; base64, <?php echo base64_encode($value['content']);?>' width='200px' alt='이미지 불러오기 실패'/></p>");
                        $("#preview") .css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px").fadeIn("fast");
                    }
                });
                //마우스에 미리보기 사진 따라가기
                $(document).on("mousemove", ".previewFile", function(e){
                        $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px");
                    });
                //마우스 아웃하면 미리보기 사진 삭제
                $(document).on("mouseout", ".previewFile", function(){
                    $("#preview").remove();
                });
            <?php
            }
            ?>
            //댓글 입력하기
            $('#boardReplyButton').click(function() {
                var replyContent = $("#boardReplyContent").val();
                $.ajax({
                    url : '../../process/boardreply.php',
                    type : 'POST',
                    dataType : 'text',
                    data : {call_name:'write_reply', boardPk:'<?php echo $post; ?>', content:replyContent},
                    error : function(jqXHR, textStatus, errorThrown){
                       alert("댓글 작성 실패했습니다. ajax 실패 원인 : " + textStatus);
                    }, success : function(result){
                        if (result) {
                            alert("댓글 작성 성공했습니다.");
                            location.reload();
                        } else {
                            alert("댓글 작성 실패했습니다.");
                        }
                    }
                });
            });
            //댓글 삭제하기
            $('.replyDelete').click(function() {
                var replyPk = $(this).val();
                $.ajax({
                    url : '../../process/boardreply.php',
                    type : 'POST',
                    dataType : 'text',
                    data : {call_name:'delete_reply', replyPk:replyPk},
                    error : function(jqXHR, textStatus, errorThrown){
                       alert("댓글 삭제 실패했습니다. ajax 실패 원인 : " + textStatus);
                    }, success : function(result){
                        if (result) {
                            alert("댓글 삭제 성공했습니다.");
                            location.reload();
                        } else {
                            alert("댓글 삭제 실패했습니다.");
                        }
                    }
                });
            });
            //수정하기
            $('#postEdit').click(function() {
               location.href = "boardedit.php?post=" + <?php echo $post; ?>;
            });
            
            //취소하기
            $('#backList').click(function() {
               location.href = "boardlist.php"; 
            });
        });
    </script>
    </body>
</html>
<?php 
} catch (Exception $e) {
    echo "<script>
            alert(\"게시글 조회에 실패했습니다! 게시글 목록 화면으로 돌아갑니다. " . $e->getMessage() . "\");
            location.href = './boardlist.php';
        </script>";;

}
?>