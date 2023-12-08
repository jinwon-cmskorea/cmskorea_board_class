<?php
    require_once './../../process/autoload.php';

    if (!session_id()) {
        session_start();
    }
    $authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
    if (!$authDBclass->isLogin()) {
        echo "<script>
                alert('로그인 실패 후 접속했습니다!');
                location.replace('../login.php');
            </script>";
    }
?>
<div class="header row bg-secondary">
    <h3 class="col-9  align-self-center fw-bold"><a class="text-white text-decoration-none" href="boardlist.php">CMSKOREA Board</a></h3>
    <span class="col-1 text-center align-self-center text-white">
        <?php echo $authDBclass->getMember()['name']; ?>
    </span>
    <button class="col-1 border-white rounded-0 btn btn-sm bg-white logoutbutton" id="logout">로그아웃</button>
</div>
<script>
    $(document).ready(function() {
        $(document).on('click', '#logout', function() {
            location.href = '../logout.php'; 
        });
    });
</script>
