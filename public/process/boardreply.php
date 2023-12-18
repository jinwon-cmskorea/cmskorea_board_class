<?php
require_once './autoload.php';

$boardDBclass = new Cmskorea_Board_Board(HOST, USERID, PASSWORD, DATABASE);
$authDBclass = new Cmskorea_Board_Auth(HOST, USERID, PASSWORD, DATABASE);

//실행할 함수 체크
if (isset($_POST['call_name']) && $_POST['call_name']) {
    switch ($_POST['call_name']){
        case  "write_reply":
            writeReply($boardDBclass, $authDBclass);
            break;
        case  "delete_reply":
            deleteReply($boardDBclass);
            break;
        default:
            break;
    }
    //call_user_func($call_name);
}
function writeReply($boardClass, $authClass) {
    try {
        if ((!isset($_POST['boardPk']) && !$_POST['boardPk']) || (!isset($_POST['content']) && !$_POST['content'])) {
            throw new Exception("게시글 등록 기능 오류 확인 : 전달받은 값 에러! 부족한 값을 입력해주세요.");
        }
        $contentReplace = array("\n","\r\n");
        
        $memberPk = $authClass->getMember();
        $insertArr = array();
        $insertArr['boardPk'] = $_POST['boardPk'];
        $insertArr['memberPk'] = $memberPk['pk'];
        $insertArr['content'] = str_replace($contentReplace, "<br>", $_POST['content']);
        echo $boardClass->addReply($insertArr);
    } catch (Exception $e) {
        echo false;
    }
}
function deleteReply($boardClass) {
    try {
        if (isset($_POST['replyPk']) && $_POST['replyPk']) {
            echo $boardClass->delReply($_POST['replyPk']);
        } else {
            echo false;
        }
    } catch (Exception $e) {
        echo false;
    }
}