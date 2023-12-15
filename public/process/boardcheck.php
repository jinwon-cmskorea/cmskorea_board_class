<?php
require_once './autoload.php';

$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);

//alert 경고창, 페이지 이동 함수
function alertReplace($message, $location) {
    echo "<script>
                alert(\"'{$message}'\");
                location.replace('{$location}');
            </script>";
}
//실행할 함수 체크
if (isset($_POST['call_name']) && $_POST['call_name']) {
    switch ($_POST['call_name']){
        case  "write_post":
            writePost($boardDBclass, $authDBclass);
            break;
        case  "update_post":
            updatePost($boardDBclass);
            break;
        case  "delete_post":
            deletePost($boardDBclass);
            break;
        default:
            break;
    }
    //call_user_func($call_name);
} else {
    alertReplace("전달 받은 값이 없습니다! 게시글 목록 화면으로 돌아갑니다.", "../view/board/boardlist.php");
}
//게시글 등록
function writePost($boardClass, $authClass) {
    try {
        if (!$_POST['writeTitle'] || !$_POST['writer']) {
            throw new Exception("게시글 등록 기능 오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        $contentReplace = array("\n","\r\n");
        
        $memberPk = $authClass->getMember();
        $insertArr = array();
        $insertArr['memberPk'] = $memberPk['pk'];
        $insertArr['title'] = $_POST['writeTitle'];
        $insertArr['writer'] = $_POST['writer'];
        $insertArr['content'] = str_replace($contentReplace, "<br>", $_POST['writeContent']);
        $postPk = $boardClass->addContent($insertArr);
        
        for ($i = 1; $i < 3; $i++) {
            //파일이 존재하면 파일 업로드
            if ($_FILES["uploadFile{$i}"]['tmp_name']) {
                $boardClass->addFile($postPk, $_FILES["uploadFile{$i}"]);
            }
        }
        alertReplace("새 글이 등록되었습니다. ", '../view/board/boardview.php?post=' . $postPk);
    } catch (Exception $e) {
        alertReplace("게시글 등록에 실패했습니다. " . $e->getMessage(), "../view/board/boardwrite.php");
    }

}
//게시글 수정
function updatePost($boardClass) {
    try{
        if (!$_POST['viewPk'] || !$_POST['updateTitle'] || !$_POST['updateWriter']) {
            throw new Exception("게시글 수정 기능 오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        $contentReplace = array("\n","\r\n");
        
        $updateArr = array();
        $updateArr['no'] = $_POST['viewPk'];
        $updateArr['title'] = $_POST['updateTitle'];
        $updateArr['writer'] = $_POST['updateWriter'];
        $updateArr['content'] = str_replace($contentReplace, "<br>", $_POST['updateContent']);
        for ($i = 1; $i < 3; $i++) {
            //파일이 존재하면 기존 파일 삭제 후 파일 업로드
            if ((isset($_POST["filePk{$i}"]) && $_POST["filePk{$i}"]) && $_FILES["uploadFile{$i}"]['tmp_name']) {
                $boardClass->delFile($_POST["filePk{$i}"]);
                $boardClass->addFile($_POST['viewPk'], $_FILES["uploadFile{$i}"]);
            } else if ($_FILES["uploadFile{$i}"]['tmp_name']) {
                $boardClass->addFile($_POST['viewPk'], $_FILES["uploadFile{$i}"]);
            }
        }
        if ($boardClass->editContent($updateArr)) {
            alertReplace("글이 수정되었습니다", '../view/board/boardview.php?post=' . $updateArr['no']);
        } else {
            throw new Exception("게시글 수정 기능 오류 확인 : SQL UPDATE 실행 에러.");
        }
    } catch (Exception $e) {
        if (isset($_POST['viewPk']) && $_POST['viewPk']) {
            alertReplace("게시글 수정에 실패했습니다 게시글 화면으로 돌아갑니다. " . $e->getMessage(), "../view/board/boardview.php?post=" . $_POST['viewPk']);
        } else {
            alertReplace("게시글 수정에 실패했습니다 게시글 목록 화면으로 돌아갑니다. " . $e->getMessage(), "../view/board/boardlist.php");
        }
    }
}
//게시글 삭제(ajax)
function deletePost($boardClass) {
    if (isset($_POST['deletePk']) && $_POST['deletePk']) {
        echo $boardClass->delContent($_POST['deletePk']);
    } else {
        echo false;
    }
}
?>