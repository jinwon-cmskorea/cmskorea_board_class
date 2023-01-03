<?php 
//     require_once ('/wwwroot/cmskorea_board_class/library/AutoLoad.php');
    include $_SERVER['DOCUMENT_ROOT'] . '/wwwroot/cmskorea_board_class/library/AutoLoad.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
    </head>
    <body>
    <?php 
        $member = new Cmskorea_Board_Member();
        $member->authenticate('aaa', 'aaa');
    ?>
    </body>
</html>