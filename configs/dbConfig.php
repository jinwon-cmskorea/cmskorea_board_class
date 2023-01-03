<?php
/**
 * @brief 데이터베이스 연결
 *
 * @param void
 * @return void
 */
$mysqli = new mysqli('192.168.0.215', 'worker-216', 'iln216', 'cmskorea_board');

if ($mysqli->connect_errno) {
    die('connect Error:: '.$mysqli->connect_error);
}
