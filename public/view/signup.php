<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script src="../css/bootstrap/js/bootstrap.js" type="javascript"></script>
    <title>회원가입</title>
</head>
<body>
    <div class="container signup-center">
        <div class="col-sm-4 fence">
            <div class="signup-title">
                <strong>씨엠에스코리아 게시판</strong><small class="subtitle">- 회원가입 -</small>
            </div>
            <form class="form-horizontal" action="../process/signupOk.php" method="post">
                <div class="form-group">
                    <label for="inputId" class="col-sm-2 category-design">아이디</label>
                    <div class="col-sm-10">
                        <input type="text" class="myForm-control2" pattern="[A-Za-z0-9]+" title="영문, 숫자" id="inputId" name="userId" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPw" class="col-sm-2 category-design">비밀번호</label>
                    <div class="col-sm-10">
                        <input type="password" class="myForm-control2" pattern="[A-Za-z0-9]+" title="영문, 숫자" id="inputPw" name="userPw" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputName" class="col-sm-2 category-design">이름</label>
                    <div class="col-sm-10">
                        <input type="text" class="myForm-control2" id="inputName" pattern="[가-힣]+" title="한글 이름만 가능합니다." name="userName" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPhone" class="col-sm-2 category-design">휴대전화</label>
                    <div class="col-sm-10">
                        <input type="text" class="myForm-control2" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" title="ex)010-0000-0000, 02-000-0000" id="inputPhone" name="userPhone" required>
                    </div>
                </div>
                <div class="signup-button">
                    <input type="submit" class="submit-btn" value="가  입">
                    <input type="button" class="cancle-btn" onclick="location.href='./login.php';" value="취  소">
                </div>
            </form>
        </div>
    </div>
</body>
</html>