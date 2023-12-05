<?php
/**
 * 테스트 파일 경로설정
 */
define('TESTS_PATH', __DIR__);
/**
 * 라이브러리 경로설정
 */
define('LIBRARY_PATH', realpath(TESTS_PATH . '/../library/'));

/**
 * 테스트 라이브러리 경로설정
 */
define('TEST_LIBRARY_PATH', realpath(TESTS_PATH . '/library/'));

/**
 * Add library path to php include path
 */
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(LIBRARY_PATH),
    realpath(TEST_LIBRARY_PATH),
    //    realpath(SCHEDULER_PATH),
    get_include_path(),
)));

session_start();