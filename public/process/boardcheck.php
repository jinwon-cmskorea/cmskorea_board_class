<?php
require_once './autoload.php';

if (!session_id()) {
    session_start();
}
$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);

function alert_replace ($message, $location) {
    echo "<script>
                alert(\"'{$message}'\");
                location.replace('{$location}');
            </script>";
}

if (isset($_POST['call_name']) && $_POST['call_name']) {
    switch ($_POST['call_name']){
        case  "write_post":
            write_post();
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
        if ((!isset($_POST['writeTitle']) && $_POST['writeTitle']) || (!isset($_POST['writer']) && $_POST['writer'])) {
            throw new Exception("오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        $contentReplace = array("\n","\r\n");
        
        $memberPk = $authDBclass->getMember();
        $insertArr = array();
        $insertArr['memberPk'] = $memberPk['pk'];
        $insertArr['title'] = $_POST['writeTitle'];
        $insertArr['writer'] = $_POST['writer'];
        $insertArr['content'] = str_replace($contentReplace, "<br>", $_POST['writeContent']);
        $postPk = $boardDBclass->addContent($insertArr);
        
        for ($i = 1; $i < 3; $i++) {
            if ($_FILES["uploadFile{$i}"]['tmp_name']) {
                $boardDBclass->addFile($postPk, $_FILES["uploadFile{$i}"]);
            }
        }
        alert_replace ("새 글이 등록되었습니다", '../view/board/boardview.php?post=' . $postPk);
    } catch (Exception $e) {
        alert_replace ("게시글 등록에 실패했습니다. " . $e->getMessage(), "../view/board/boardwrite.php");
    }

}

function update_post() {
    global $boardDBclass;
    try{
        if ((!isset($_POST['viewPk']) && $_POST['viewPk']) || (!isset($_POST['updateTitle']) && $_POST['updateTitle']) 
                || (!isset($_POST['updateWriter']) && $_POST['updateWriter'])) {
            throw new Exception("오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요." . $_POST['updateWriter']);
        }
        $contentReplace = array("\n","\r\n");
        
        $updateArr = array();
        $updateArr['no'] = $_POST['viewPk'];
        $updateArr['title'] = $_POST['updateTitle'];
        $updateArr['writer'] = $_POST['updateWriter'];
        $updateArr['content'] = str_replace($contentReplace, "<br>", $_POST['updateContent']);
        
        if ($boardDBclass->editContent($updateArr)) {
            alert_replace ("글이 수정되었습니다", '../view/board/boardview.php?post=' . $updateArr['no']);
        } else {
            throw new Exception("오류 확인 : SQL UPDATE 실행 에러.");
        }
    } catch (Exception $e) {
        if (isset($_POST['viewPk'])) {
            alert_replace ("게시글 수정에 실패했습니다 게시글 화면으로 돌아갑니다. " . $e->getMessage(), "../view/board/boardview.php?post=" . $_POST['viewPk']);
        } else {
            alert_replace ("게시글 수정에 실패했습니다 게시글 목록 화면으로 돌아갑니다. " . $e->getMessage(), "../view/board/boardlist.php");
        }
    }
}

function delete_post() {
    global $boardDBclass;
    
    echo $boardDBclass->delContent($_POST['deletePk']);
}
?>