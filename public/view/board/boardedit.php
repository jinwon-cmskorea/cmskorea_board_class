<?php 
require_once './../../process/autoload.php';
$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
if (!session_id()) {
    session_start();
}
if (isset($_GET['post']) && $_GET['post']) {
    $post = $_GET['post'];
} else {
    $post = 0;
}
//수정 유저 확인, 수정할 게시글 받아오기
try {
    $postlist = $boardDBclass->getContent($post);
    $userdata = $authDBclass->getMember();
    if ($postlist['memberPk'] !== $userdata['pk'] && $userdata['id'] !== "root") {
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
                    <form class="mb-4" method="post" action="../../process/boardcheck.php" id="editForm" onsubmit="return checkForm();">
                        <input type="hidden" name="call_name" value="update_post">
                        <input type="hidden" name="viewPk" value="<?php echo $post;?>">
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">제 목</span>
                            </div>
                            <input type="text" class="col-9 inputwritebox align-self-center" name="updateTitle" id="editTitle" placeholder="제목을 입력해주세요." value="<?php echo $postlist['title'];?>">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 mb-5 my-2">
                                <span class="text-white">내 용</span>
                            </div>
                            <textarea  class="col-9 inputwritebox my-2" style="height: 320px; resize: none;" name="updateContent" id="editContent"  placeholder="내용을 입력해주세요."><?php echo str_replace("<br>", "\n", $postlist['content']); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">작성자</span>
                            </div>
                            <input type="text" class="col-2 text-secondary inputwritebox align-self-center" name="updateWriter" id="writer" value="<?php echo $postlist['writer']; ?>">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white" style="font-size:15px">파일업로드</span>
                            </div>
                            <input type="file" class="col-6 align-self-center" id="file1">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2" >
                                <span class="text-white" style="font-size:15px">파일업로드</span>
                            </div>
                            <input type="file" class="col-6 align-self-center" id="file2">
                        </div>
                    </form>
                    <div class="mx-5 row">
                        <button type="submit" form="editForm" class="btn btn-primary bg-warning border-warning col rounded-0 mx-1" id="postEdit">수정</button>
                        <button class="col mx-1" style="border: solid 1px lightgray;" id="backPost">취소</button>
                    </div>
                </div>
                <div class="text-start" id="alertBox"></div>
            </div>
        </div>
    <script>
        function checkForm() {
        //경고문(입력 체크)  
            const appendAlert = (message, type, id) => {
                const alertPlaceholder = document.getElementById(id);
                const wrapper = document.createElement('div');
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible alertmainbox" id="alertmain" >`,
                    `   <div>${message}</div>`,
                    '   <button type="button" id="alertclose" class="btn-close close" data-bs-dismiss="alert"></button>',
                    '</div>'
                ].join('')
                    
                alertPlaceholder.append(wrapper);
            }
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
            //취소하기
            $('#backPost').click(function() {
               location.href = "boardview.php?post=" + <?php echo $post;?>;
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