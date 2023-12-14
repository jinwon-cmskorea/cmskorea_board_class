<?php
//클래스 오토로드
spl_autoload_register(function ($className) {
    $dirPath = str_replace('\\', '/', __DIR__);
    $className = str_replace('_', '/', $className);
    
    require_once $dirPath . '/../../configs/dbconfigs.php';
    include $dirPath . '/../../library/' . $className . '.php';
});