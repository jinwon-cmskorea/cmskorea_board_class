<?php
require_once './../../process/autoload.php';

$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);
//로그인 체크
if (!$authDBclass->isLogin()) {
    echo "<script>
            alert('로그인 실패 후 접속했습니다!');
            location.replace('../login.php');
        </script>";
}
?>
<div class="header row bg-secondary">
    <h3 class="col-7  align-self-center fw-bold"><a class="text-white text-decoration-none" href="boardlist.php">CMSKOREA Board Project</a></h3>
    <span class="col-3 text-end align-self-center text-white">
        <?php echo $authDBclass->getMember()['name']; ?>
    </span>
    <button class="col-1 border-white rounded-0 btn btn-sm bg-white logoutbutton" id="logout">로그아웃</button>
</div>
<script>
    $(document).ready(function() {
        //로그아웃
        $('#logout').click(function() {
            location.href = '../logout.php';
        });
    });
</script>
