<?php
spl_autoload_register(function ($className) {
    $dirpath = str_replace('\\', '/', __DIR__);
    $className = str_replace('_', '/', $className);
    
    require_once $dirpath . '/../../configs/dbconfigs.php';
    include $dirpath . '/../../library/' . $className . '.php';
});