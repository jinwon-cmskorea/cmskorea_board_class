<?php 
    if(!session_id()) {
        session_start();
    }
?>
<div class="header row bg-secondary">
    <h3 class="col-9  align-self-center fw-bold"><a class="text-white text-decoration-none" href="boardlist.php">CMSKOREA Board</a></h3>
    <span class="col-1 text-center align-self-center text-white"><?php print_r($_SESSION['userName']); ?></span>
    <button class="col-1 border-white rounded-0 btn btn-sm bg-white logoutbutton" id="logout">로그아웃</button>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click', '#logout',function(){
            location.href = '../logout.php'; 
        });
    });
</script>
