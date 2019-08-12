<?php

//phpinfo();
//return;
ini_set('error_log',__DIR__.'/error.log');
//var_export($_SERVER);
if (!isset($_SERVER['HTTP_AUTHORIZATION']) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])){
    $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}
include '../bootstrap.php';