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
                height: 360px;
            }
        </style>
        <title>로그인 페이지</title>
    </head>
    <body>
        <div class="container">
            <div  class="text-center centerbox bg-secondary-subtle">
                <div>
                    <h4 class="pagetitle">CMSKOREA Board</h4>
                    <hr/>
                    <p class="text-secondary">아이디 / 비밀번호를 입력하여 주세요.</p>
                </div>
                <form method="post" action="../process/logincheck.php" id="loginForm" onsubmit="return checkForm();">
                    <div class="text-start grid gap-3">
                        <div class="row p-2 g-col-6">
                            <span class="col-3 align-self-center ">아이디</span>
                            <input  type="text" class="col-8 form-control border-dark-subtle rounded-0" style="width: 70%;" id="name" name="name">
                        </div>
                        <div class="row p-2 g-col-6">
                            <span class="col-3 align-self-center">비밀번호</span>
                            <input  type="password" class="col-8 form-control border-dark-subtle rounded-0" style="width: 70%;" id="password" name="password">
                        </div>
                    </div>
                    <div class="d-grid gap-2 p-2 g-col-6">
                        <button type="submit" class="btn btn-primary btn-lg rounded-0" id="loginButton">로그인</button>
                    </div>
                    <div class="row justify-content-end">
                            <div class="col-5">
                            </div>
                        <button type="button" class="btn btn-primary col-2 p-2 g-col-6 rounded-0" style="margin-right: 20px;" id="signupHTML">회원가입</button>
                    </div>
                </form>
                <div class="text-start mt-4" id="alertBox"></div>
            </div>
        </div>
        <script type="text/javascript" src="../js/appendAlert.js"></script>
        <script>
            function checkForm() {
                //경고문 (input 입력)
                var check = false;
                var loginName = $("#name").val();
                var loginPassword = $("#password").val();
                
                if (!loginName) {
                    $("#alertBox").empty();
                    appendAlert('&#9888;아이디를 입력해 주세요!', 'danger', 'alertBox');
                    return check;
                } else if(!loginPassword) {
                   $("#alertBox").empty();
                   appendAlert('&#9888;비밀번호를 입력해 주세요!', 'danger', 'alertBox');
                   return check;
                } else {
                    check = true;
                }
            return check;
            }
            $(document).ready(function() {
                $('#name').focus();
                
                //경고창 input focus 자동 삭제
                $('.form-control').click(function() {
                    $('#alertBox').empty();
                });
                //회원가입 화면
                $('#signupHTML').click(function() {
                    location.href = 'signup.php'; 
                });
            }); 
        </script>
    </body>
</html>