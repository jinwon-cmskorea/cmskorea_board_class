<?php
require_once './autoload.php';

if (!session_id()) {
    session_start();
}
$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$boardDBconnect = mysqli_connect(HOST, USERID, PASSWORD, DATABASE);
if (mysqli_connect_errno()) {
    $errorcode = mysqli_connect_error();
    echo "<script>
            alert('DB 연결에 실패했습니다! 리스트로 돌아갑니다. {$errorcode}');
            location.replace('../view/board/boardlist.php');
        </script>";
}
function write_post() {
    global $boardDBclass;
    global $boardDBconnect;
    
    $findMemberPk = mysqli_query($boardDBconnect,
            "SELECT pk FROM member WHERE id='" . $_SESSION[Cmskorea_Board_Auth::SESSION_NAMESPACE]['id'] . "';");
    $memberPk = mysqli_fetch_row($findMemberPk)[0];
    $strip = mysqli_real_escape_string($boardDBconnect, strip_tags($_POST['writeContent'], '<br>'));

    $insertArr = array();
    $insertArr['memberPk'] = $memberPk;
    $insertArr['title'] = mysqli_real_escape_string($boardDBconnect, $_POST['writeTitle']);
    $insertArr['writer'] = mysqli_real_escape_string($boardDBconnect, $_POST['writer']);
    $insertArr['content'] = $strip;

    if ($findMemberPk) {
        $postPk = $boardDBclass->addContent($insertArr);
        echo $postPk;
    } else {
        echo "등록실패 : " . mysqli_error($boardDBconnect);
    }
}

function view_post() {
    global $boardDBclass;
    
    $row = $boardDBclass->getContent($_POST['viewPk']);
    echo json_encode($row);
}

function update_post() {
    global $boardDBclass;
    global $boardDBconnect;
    
    $strip = mysqli_real_escape_string($boardDBconnect, strip_tags($_POST['updateContent'], '<br>'));
    
    $updateArr = array();
    $updateArr['no'] = $_POST['viewPk'];
    $updateArr['title'] = mysqli_real_escape_string($boardDBconnect, $_POST['updateTitle']);
    $updateArr['writer'] = mysqli_real_escape_string($boardDBconnect, $_POST['updateWriter']);
    $updateArr['content'] = $strip;
    
    echo $boardDBclass->editContent($updateArr);
}

function delete_post() {
    global $boardDBclass;
    
    $row = $boardDBclass->delContent($_POST['deletePk']);
    echo json_encode($row);
}
if (isset($_POST['call_name']) && $_POST['call_name']) {
    $call_name = $_POST['call_name'];
    call_user_func($call_name);
} else {
    echo "전달 받은 값이 없습니다!";
}
?>