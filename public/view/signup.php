<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../js/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="../css/bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <title>회원가입</title>
    <script type="text/javascript">
        $(document).ready(function() {
            //초기 제출 버튼 배경색 설정
            $(".submit-btn").css("background-color", "rgb(200, 200, 200)");
            //id 중복 체크 변수
            var idOk = false;
            //아이디를 입력받는 #inputId 에서 keyup 이벤트 발생 시
            $("#inputId").on("keyup", function() {
                var self = $(this);
                var userId = self.val();
                //ajax를 통해 checkDup.php 에 입력된 id 전송
                $.ajax({
                    url: "../process/checkIdDup.php",
                    method: "POST",
                    data: {'userId' : userId},
                    dataType: "json",
                    success: function(receive) {
                        if (receive.status == 1) {
                            alert("중복된 아이디입니다.");
                            self.focus();
                            idOk = false;
                        } else {
                            idOk = true;
                        }
                    }
                });
            });
            
            //입력값이 모두 입력됐는지 확인하기 위한 변수
            var isEmpty = true;
            //각 input 이 변경되었을 때 실행
            $("#signUpForm").find("input[type=text], input[type=password]").change(function() {
                var Elements = $("#signUpForm input[type=text], #signUpForm input[type=password]");
                for (var i = 0; i < Elements.length; i++)
                {
                    //비어있지 않으면 false 입력, 비어있으면 true 입력
                    if ($(Elements[i]).val() != "" && $(Elements[i]).val() != null) {
                        isEmpty = false;
                    } else {
                        isEmpty = true;
                        break;
                    }
                }
                //입력값이 모두 들어있고, 아이디 중복체크를 통과했으면 제출 비활성화 해제
                if (!isEmpty && idOk) {
                    $(".submit-btn").attr("disabled", false);
                    $(".submit-btn").css("background-color", "rgb(112, 173, 71)");
                } else {
                    $(".submit-btn").attr("disabled", true);
                    $(".submit-btn").css("background-color", "rgb(200, 200, 200)");
                }
            });
        });
    </script>
</head>
<body>
    <div class="container signup-center">
        <div class="col-sm-4 fence">
            <div class="signup-title">
                <strong>씨엠에스코리아 게시판</strong><small class="subtitle">- 회원가입 -</small>
            </div>
            <form id="signUpForm" class="form-horizontal" action="../process/signupOk.php" method="post">
                <div class="form-group">
                    <label for="inputId" class="col-sm-2 category-design">아이디</label>
                    <div class="col-sm-10">
                        <input type="text" class="myForm-control2" pattern="[A-Za-z0-9]+" title="영문, 숫자" id="inputId" name="userId" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPw" class="col-sm-2 category-design">비밀번호</label>
                    <div class="col-sm-10">
                        <input type="password" class="myForm-control2" pattern="[A-Za-z0-9~`!@#$%\^&*()-+=]+" title="영문, 숫자, 특수문자 1개이상" id="inputPw" name="userPw" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 category-design">이름</label>
                    <div class="col-sm-10">
                        <input type="text" class="myForm-control2" id="inputName" pattern="[A-Za-z가-힣]+" title="한글, 영어 이름만 가능합니다." name="userName" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPhone" class="col-sm-2 category-design">휴대전화</label>
                    <div class="col-sm-10">
                        <input type="text" class="myForm-control2" pattern="[0-9]{3}-[0-9]{3,4}-[0-9]{4}" title="ex)000-0000-0000 또는 000-000-0000" id="inputPhone" name="userPhone" required>
                    </div>
                </div>
                <div class="signup-button">
                    <input type="submit" class="submit-btn" value="가  입" disabled>
                    <input type="button" class="cancle-btn" onclick="location.href='./login.php';" value="취  소">
                </div>
            </form>
        </div>
    </div>
</body>
</html>