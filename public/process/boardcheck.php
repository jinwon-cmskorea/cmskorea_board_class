<?php
require_once './autoload.php';

if (!session_id()) {
    session_start();
}
$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);

if (isset($_POST['call_name']) && $_POST['call_name']) {
    switch ($_POST['call_name']){
        case  "write_post":
            write_post();
            break;
        case  "view_post":
            view_post();
            break;
        case  "update_post":
            update_post();
            break;
        case  "delete_post":
            delete_post();
            break; 
    }
    //call_user_func($call_name);
} else {
    echo "전달 받은 값이 없습니다!";
}
function write_post() {
    global $boardDBclass;
    global $authDBclass;
    try {
        $memberPk = $authDBclass->getMember();
        $insertArr = array();
        $insertArr['memberPk'] = $memberPk['pk'];
        $insertArr['title'] = $_POST['writeTitle'];
        $insertArr['writer'] = $_POST['writer'];
        $insertArr['content'] = $_POST['writeContent'];
        $postPk = $boardDBclass->addContent($insertArr);
        echo $postPk;
    } catch (Exception $e) {
        echo "게시글 등록에 실패했습니다. " . $e->getMessage();
    }
}

function view_post() {
    global $boardDBclass;
    try {
        $row = $boardDBclass->getContent($_POST['viewPk']);
        echo json_encode($row);
    } catch (Exception $e) {
        $makeArray = array('errorMessage'=> $e->getMessage());
        echo json_encode($makeArray);
    }
}

function update_post() {
    global $boardDBclass;

    $updateArr = array();
    $updateArr['no'] = $_POST['viewPk'];
    $updateArr['title'] = $_POST['updateTitle'];
    $updateArr['writer'] = $_POST['updateWriter'];
    $updateArr['content'] = $_POST['updateContent'];
    
    echo $boardDBclass->editContent($updateArr);
}

function delete_post() {
    global $boardDBclass;
    
    echo $boardDBclass->delContent($_POST['deletePk']);
}
?>