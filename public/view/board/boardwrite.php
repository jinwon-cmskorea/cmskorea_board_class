<?php 
require_once './../../process/autoload.php';

try {
    $authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
    $memberData = $authDBclass->getMember();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="../../js/jQuery/jquery-3.6.3.min.js"></script>
        <link href="../../css/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../../css/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../../css/main.css" type="text/css">
        <title>작성 페이지</title>
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
                    <span class="text-primary text-opacity-75 pagedescription">- 작성 -</span>
                </div>
                <div class="border rounded border-dark-subtle align-self-center descriptionlinebox">
                    <p>게시판 글을 작성합니다.</p>
                </div>
                <div class="p-4">
                    <form method="post" enctype="multipart/form-data" action="../../process/boardcheck.php" id="writeForm" onsubmit="return checkForm();">
                        <input type="hidden" name="call_name" value="write_post">
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">제 목</span>
                            </div>
                            <input type="text" class="col-9 inputwritebox align-self-center" name="writeTitle" id="writeTitle" placeholder="제목을 입력해주세요.">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 mb-5 my-2">
                                <span class="text-white">내 용</span>
                            </div>
                            <textarea  class="col-9 inputwritebox my-2" style="height: 250px; resize: none;" name="writeContent" id="writeContent"  placeholder="내용을 입력해주세요."></textarea>
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">작성자</span>
                            </div>
                            <input type="text" class="col-2 text-secondary inputwritebox align-self-center" name="writer" id="writer" value="<?php echo $memberData['name'] ?>">
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
                        <div class="mb-3 row text-danger">※파일 제한 용량 : 3MB이하 | 확장자 : jpg, png, gif, pdf</div>
                    </form>
                    <div class="mx-5 row">
                        <button type="submit" form="writeForm" class="btn btn-primary col rounded-0 mx-1" id="boardWrite">작성</button>
                        <button class="col mx-1" style="border: solid 1px lightgray;" id="writeCancel">취소</button>
                    </div>
                </div>
                <div class="text-start" id="alertBox"></div>
            </div>
        </div>
    <script type="text/javascript" src="../../js/appendAlert.js"></script>
    <script>
        //경고문(입력 체크)  
        function checkForm() {
            var check = false;
            var writeTitle = $("#writeTitle").val();
            var writeContent = $("#writeContent").val();
            var writer = $("#writer").val();
            
            //input 검사
            if (!writeTitle) {
                $("#alertBox").empty();
                appendAlert('&#9888;제목을 입력해 주세요!', 'danger', 'alertBox');
                return check;
            } else if (!writeContent) {
                $("#alertBox").empty();
                appendAlert('&#9888;내용을 입력해 주세요!', 'danger', 'alertBox');
                return check;
            } else if (!writer) {
                $("#alertBox").empty();
                appendAlert('&#9888;작성자를 입력해 주세요!', 'danger', 'alertBox');
                return check;
            } else {
                check = true;
            }
            return check;
        }
        $(document).ready(function () {
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
                    alert("'jpg, png, gif, pdf' 파일만 업로드 할수 있습니다.");
                    $(this).val("");
                }
            });
            //취소하기
            $('#writeCancel').click(function() {
               location.href = 'boardlist.php'; 
            });
        });
    </script>
    </body>
</html>
<?php 
} catch (Exception $e) {
    echo "<script>
            alert(\"게시글 작성 페이지 접속 실패했습니다! 게시글 목록 화면으로 돌아갑니다. " . $e->getMessage() . "\");
            location.href = './boardlist.php';
        </script>";;
}
?>