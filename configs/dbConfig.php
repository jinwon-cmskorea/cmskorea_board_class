<?php
/**
 * @brief 데이터베이스 연결
 *
 * @param void
 * @return void
 */
require_once 'dbInfo.php';

function dbCon() {
    $mysqli = new mysqli(DBHOST, USERNAME, USERPW, DBNAME);
    
    if ($mysqli->connect_errno) {
        die('connect Error:: '.$mysqli->connect_error);
    }
    
    return ($mysqli);
}
