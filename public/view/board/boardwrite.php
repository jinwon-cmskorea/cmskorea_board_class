<?php 
require_once './../../process/autoload.php';
if (!session_id()) {
    session_start();
}
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
                    <div class="mb-4">
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">제 목</span>
                            </div>
                            <input type="text" class="col-9 inputwritebox align-self-center" id="writeTitle" placeholder="제목을 입력해주세요.">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 mb-5 my-2">
                                <span class="text-white">내 용</span>
                            </div>
                            <textarea  class="col-9 inputwritebox my-2" style="height: 320px; resize: none;" id="writeContent"  placeholder="내용을 입력해주세요."></textarea>
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">작성자</span>
                            </div>
                            <input type="text" class="col-2 text-secondary inputwritebox align-self-center" id="writer" value="<?php echo $memberData['name'] ?>">
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
                    </div>
                    <div class="mx-5 row">
                        <button class="btn btn-primary col rounded-0 mx-1" id="boardWrite">등 록</button>
                        <button class="col mx-1" style="border: solid 1px lightgray;" id="writeCancel">취소</button>
                    </div>
                </div>
                <div class="text-start" id="alertBox"></div>
            </div>
        </div>
    <script>
        $(document).ready(function () {
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
            //작성 버튼
            $(document).on('click', '#boardWrite',function() {
                var writeTitle = $("#writeTitle").val();
                var writeContent = $("#writeContent").val().replaceAll(/(\n|\r\n)/g, "<br>");
                var writer = $("#writer").val();
                //input 검사
                if (!writeTitle) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;제목을 입력해 주세요!', 'danger', 'alertBox');
                } else if (!writeContent) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;내용을 입력해 주세요!', 'danger', 'alertBox');
                } else if (!writer) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;작성자를 입력해 주세요!', 'danger', 'alertBox');
                } else {
                    $.ajax({
                        url : '../../process/boardcheck.php',
                        type : 'POST',
                        dataType : 'text',
                        data : {call_name:'write_post', writeTitle:writeTitle, writeContent:writeContent, writer:writer},
                        error : function(jqXHR, textStatus, errorThrown){
                            console.log("실패");
                            alert("게시글 등록에 실패했습니다. ajax 실패 원인 : " + textStatus);
                        }, success : function(result) {
                            if (result > 0) {
                                alert('새 글이 등록되었습니다');
                                location.href = "boardview.php?" + result; 
                            } else {
                                $(".alertmainbox").remove();
                                appendAlert('&#9888;' + result, 'danger', 'alertBox');
                            }
                        }
                    });
                }
            });
            //취소하기
            $(document).on('click', '#writeCancel',function() {
               location.href = 'boardlist.php'; 
            });
        });
    </script>
    </body>
</html>