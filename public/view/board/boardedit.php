<?php 
require_once './../../process/autoload.php';

$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);

if (isset($_GET['post']) && $_GET['post']) {
    $post = $_GET['post'];
} else {
    $post = 0;
}
//수정 유저 권한 확인, 수정할 게시글 받아오기
try {
    $postList = $boardDBclass->getContent($post);
    $fileList = $boardDBclass->getFiles($post);
    $userData = $authDBclass->getMember();
    if ($postList['memberPk'] !== $userData['pk'] && $userData['id'] !== "root") {
        echo "<script>
                    alert('잘못된 수정 페이지 접근입니다! 게시글 목록으로 돌아갑니다.');
                    location.replace('./boardlist.php');
                </script>";
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="../../js/jQuery/jquery-3.6.3.min.js"></script>
        <link href="../../css/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../../css/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../../css/main.css" type="text/css">
        <title>수정 페이지</title>
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
                    <span class="text-primary text-opacity-75 pagedescription">- 수정 -</span>
                </div>
                <div class="border rounded  border-dark-subtle align-self-center descriptionlinebox">
                    <p>게시판 글을 수정합니다.</p>
                </div>
                <div class="p-4">
                    <form method="post" enctype="multipart/form-data" action="../../process/boardcheck.php" id="editForm" onsubmit="return checkForm();">
                        <input type="hidden" name="call_name" value="update_post">
                        <input type="hidden" name="viewPk" value="<?php echo $post; ?>">
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">제 목</span>
                            </div>
                            <input type="text" class="col-9 inputwritebox align-self-center" name="updateTitle" id="editTitle" placeholder="제목을 입력해주세요." value="<?php echo $postList['title']; ?>">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 mb-5 my-2">
                                <span class="text-white">내 용</span>
                            </div>
                            <textarea  class="col-9 inputwritebox my-2" style="height: 250px; resize: none;" name="updateContent" id="editContent"  placeholder="내용을 입력해주세요."><?php echo str_replace("<br>", "\n", $postList['content']); ?></textarea>
                        </div>
                            <?php
                            if ($fileList) {
                            ?>
                            <div class="row">
                                <div class="labelbox text-center col-1 mx-5 my-2">
                                    <span class="text-white" style="font-size:15px">업로드파일</span>
                                </div>
                                <div class="col-9">
                                    <ul class="fileUl">
                                    <?php 
                                    $index = 1;
                                    foreach ($fileList as $value) {
                                    ?>
                                        <input type="hidden" name="filePk<?php echo $index; ?>" value="<?php echo $value['pk']; ?>">
                                        <li><?php echo $value['filename']; ?><button type='button' class='btn btn-sm btn-danger ms-1 deleteFileButton' value="<?php echo $value['pk']; ?>">삭제</button></li>
                                    <?php 
                                        $index++;
                                    }
                                    ?>
                                    </ul>
                                </div>
                            </div>
                            <?php 
                            }
                            ?>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">작성자</span>
                            </div>
                            <input type="text" class="col-2 text-secondary inputwritebox align-self-center" name="updateWriter" id="writer" value="<?php echo $postList['writer']; ?>">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white" style="font-size:15px">파일업로드</span>
                            </div>
                            <input type="file" class="col-6 align-self-center checkFile" name="uploadFile1" id="uploadFile1">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2" >
                                <span class="text-white" style="font-size:15px">파일업로드</span>
                            </div>
                            <input type="file" class="col-6 align-self-center checkFile" name="uploadFile2" id="uploadFile2">
                        </div>
                        <div class="text-danger">※파일 제한 용량 : 3MB이하 | 확장자 : jpg, png, gif, pdf</div>
                    </form>
                    <div class="m-3 d-flex">
                        <div class="fw-bold">
                            <div>마지막 수정시간</div>
                        </div>
                        <div class="ms-1">
                            <div><span id="boardViewUpdateTime">: <?php echo $postList['updateTime']; ?></span></div>
                        </div>
                    </div>
                    <div class="mx-5 row">
                        <button type="submit" form="editForm" class="btn btn-primary bg-warning border-warning col rounded-0 mx-1" id="postEdit">수정</button>
                        <button class="col mx-1" style="border: solid 1px lightgray;" id="backPost">취소</button>
                    </div>
                </div>
                <div class="text-start" id="alertBox"></div>
            </div>
        </div>
    <script type="text/javascript" src="../../js/appendAlert.js"></script>
    <script>
        function checkForm() {
        //경고문(입력 체크)  
            var check = false;
            var updateTitle = $('#editTitle').val();
            var updateContent = $('#editContent').val();
            var updateWriter = $('#writer').val();
            //input 검사
            if (!updateTitle) {
                $("#alertBox").empty();
                appendAlert('&#9888;제목을 입력해 주세요!', 'danger', 'alertBox');
                return check;
            } else if (!updateContent) {
                $("#alertBox").empty();
                appendAlert('&#9888;내용을 입력해 주세요!', 'danger', 'alertBox');
                return check;
            } else if (!updateWriter) {
                $("#alertBox").empty();
                appendAlert('&#9888;작성자를 입력해 주세요!', 'danger', 'alertBox');
                return check;
            } else {
                check = true;
            }
            return check;
        }
        $(document).ready(function () {
            //게시글 삭제
            $(document).on('click', 'body .deleteFileButton', function() {
                deletePk = $(this).attr("value");
                $.ajax({
                    url : '../../process/fileDelete.php',
                    type : 'POST',
                    dataType : 'text',
                    data : {deletePk:deletePk},
                    error : function(jqXHR, textStatus, errorThrown){
                       alert("파일 삭제 실패했습니다. ajax 실패 원인 : " + textStatus);
                    }, success : function(result){
                        if (result) {
                            alert("파일 삭제 실패했습니다. 실패 원인 :" + result);
                         } else {
                             alert("파일 삭제 성공했습니다.");
                             location.reload();
                         }
                    }
                 });
            });
            //파일 업로드 용량, 확장자 체크
            $(".checkFile").change(function(){
                var uploadFile = $(this).val();
                var fileSize = $(this)[0].files[0].size;
                var maxSize = 3 * 1024 * 1024;
                var ext = uploadFile.split('.').pop().toLowerCase();
                if (fileSize > maxSize) {
                    alert("첨부파일 사이즈는 3MB 이내로 등록 가능합니다.");
                    $(this).val("");
                }
                if ($.inArray(ext, ['jpg','png','gif','pdf']) == -1) {
                    alert("'jpg,gif,jpeg,png' 파일만 업로드 할수 있습니다.");
                    $(this).val("");
                }
            });
            //취소하기
            $('#backPost').click(function() {
               location.href = "boardview.php?post=" + <?php echo $post; ?>;
            });
        });
    </script>
    </body>
</html>
<?php 
} catch (Exception $e) {
    echo "<script>
            alert(\"게시글 수정 조회에 실패했습니다! 게시글 화면으로 돌아갑니다. " . $e->getMessage() . "\");
            location.href = './boardview.php?post='{$post};
        </script>";
}
?>