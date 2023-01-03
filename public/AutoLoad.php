<?php 
spl_autoload_register(function($className) {
    $className = str_replace('_', '/', $className);
    require_once  __DIR__ . '/../library/' . $className . '.php';
});