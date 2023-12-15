<?php 
require_once './../process/autoload.php';

$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
if ($authDBclass->logout()) {
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="../js/jQuery/jquery-3.6.3.min.js"></script>
        <link href="../css/bootstrap-5.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../css/bootstrap-5.3.1-dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../css/main.css" type="text/css">
        <style type="text/css">
            .centerbox{
                width: 570px;
                height: 250px;
            }
        </style>
        <title>로그아웃 페이지</title>
    </head>
    <body>
        <div class="container">
            <div  class="text-center centerbox bg-secondary-subtle">
                <div>
                    <h4 class="pagetitle">CMSKOREA Board</h4>
                    <hr>
                    <p class="fs-4 text-black-50">로그아웃 되었습니다.</p>
                </div>
                <div class="d-flex justify-content-between align-items-end mt-5">
                    <span class="text-secondary">3초 후 처음 화면으로 이동합니다...</span>
                    <button class="btn btn-primary btn-lg rounded-0" id="home">확인</button>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                //3초 후 자동으로 index.php로 이동
                setTimeout(function() { 
                    location.href = './../index.php'; 
                }, 3000);
                //확인 버튼 클릭 후 이동
                $('#home').click(function() {
                    location.href = './../index.php'; 
                });
            });
        </script>
    </body>
</html>
<?php     
} else {
    echo "<script>
            alert('로그아웃에 실패했습니다. 리스트 화면으로 돌아갑니다.');
            location.replace('./board/boardlist.php');
        </script>";;
}
?>