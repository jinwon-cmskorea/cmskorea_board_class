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
                    <div class="mb-4">
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">제 목</span>
                            </div>
                            <input type="text" class="col-9 inputwritebox align-self-center" id="editTitle" placeholder="제목을 입력해주세요.">
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 mb-5 my-2">
                                <span class="text-white">내 용</span>
                            </div>
                            <textarea  class="col-9 inputwritebox my-2" style="height: 400px; resize: none;" id="editContent"  placeholder="내용을 입력해주세요."></textarea>
                        </div>
                        <div class="row">
                            <div class="labelbox text-center col-1 mx-5 my-2">
                                <span class="text-white">작성자</span>
                            </div>
                            <input type="text" class="col-2 inputwritebox align-self-center" id="writer" readonly >
                        </div>
                    </div>
                    <div class="mx-5 row">
                        <button class="btn btn-primary bg-warning border-warning col rounded-0 mx-1" id="postEdit">수정</button>
                        <button class="col mx-1" style="border: solid 1px lightgray;" id="backPost">취소</button>
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
            const viewPk = location.href.split('?')[1];
            //게시글 조회
            function setViewData(){
                $.ajax({
                    url : '../../process/board.php',
                    type : 'POST',
                    dataType : 'json',
                    data : {call_name:'view_post', viewPk:viewPk},
                    error : function(){
                        console.log("실패");
                    }, success : function(result){
                        $('#editTitle').val(result['title']);
                        $('#editContent').val(result['content'].replaceAll("<br>","\n"));
                        $('#writer').val(result['writer']);
                    }
                });
            }
            setViewData()
            //수정 완료하기
            $(document).on('click', '#postEdit',function(){
                var updateTitle = $('#editTitle').val();
                var updateContent = $('#editContent').val();
                var updateWriter = $('#writer').val();
                updateContent = updateContent.replaceAll(/(\n|\r\n)/g, "<br>");
                
                //input 검사
                if (!updateTitle) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;제목을 입력해 주세요!', 'danger', 'alertBox');
                } else if (!updateContent) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;내용을 입력해 주세요!', 'danger', 'alertBox');
                } else if (!updateWriter) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;작성자를 입력해 주세요!', 'danger', 'alertBox');
                } else {
                    $.ajax({
                        url : '../../php/board.php',
                        type : 'POST',
                        dataType : 'text',
                        data : {call_name:'update_post', viewPk:viewPk, updateTitle:updateTitle, updateContent:updateContent, updateWriter:updateWriter},
                        error : function(){
                            console.log("실패");
                        }, success : function(result){
                            console.log(result);
                            if (!result) {
                                alert('글이 수정되었습니다');
                              location.href = "boardview.php?" + viewPk;
                            } else {
                                $(".alertmainbox").remove();
                                appendAlert('&#9888;게시글 수정에 실패했습니다!', 'danger', 'alertBox');
                            }
                        }
                    });
                }
            });
            
            //취소하기
            $(document).on('click', '#backPost',function(){
               location.href = "boardview.php?" + viewPk;
            });
        });
    </script>
    </body>
</html>