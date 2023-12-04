<?php
require_once "../../configs/dbconn.php";

if (!session_id()) {
    session_start();
}

function write_post() {
    $writeTitle = $_POST['writeTitle'];
    $writeContent = $_POST['writeContent'];
    $writer = $_POST['writer'];
    $boardDBclass = new DBconn();
    
    $strip = mysqli_real_escape_string($boardDBclass->getDBconnect(), strip_tags($writeContent, '<br>'));
    $find = $boardDBclass->Dbresult("member", "id", $writer, "pk");
    $memberPk = mysqli_num_rows($find);
    
    if ($memberPk) {
        $iferror = $boardDBclass->getDbInsert('board', "( memberPk, title, writer, content, insertTime, updateTime)", "( ". $memberPk ." ,'". $writeTitle ."' ,'". $writer ."', '" . $strip . "' , now(), now())");
        if (isset($iferror)) {
            echo $iferror;
        }
    } else {
        echo "등록실패 : " . mysqli_error($boardDBclass->getDBconnect());
    }
}

function view_post() {
    $viewPk = $_POST['viewPk'];
    
    $boardDBclass = new DBconn();
    $row = $boardDBclass->getDbData("board", "pk", $viewPk, "*");
    echo json_encode($row);
}

function update_post() {
    $viewPk = $_POST['viewPk'];
    $updateTitle = $_POST['updateTitle'];
    $updateContent = $_POST['updateContent'];
    $updateWriter = $_POST['updateWriter'];
    
    $boardDBclass = new DBconn();
    $strip = mysqli_real_escape_string($boardDBclass->getDBconnect(), strip_tags($updateContent, '<br>'));
    
    $iferror = $boardDBclass->getDbUpdate("board", "title='" . $updateTitle. "', content='"  . $strip . "', writer='" . $updateWriter . "', updateTime= now()", "pk", $viewPk);
    if (isset($iferror)) {
        echo $iferror;
    }
}

function delete_post() {
    $deletePk = $_POST['deletePk'];
    $boardDBclass = new DBconn();
    $iferror = $boardDBclass->getDbDelete("board", "pk", $deletePk);
    if (isset($iferror)) {
        echo $iferror;
    }
}
if (isset($_POST['call_name'])) {
    $call_name = $_POST['call_name'];
    call_user_func($call_name);
} else {
    echo "전달 받은 값이 없습니다!";
}
?>