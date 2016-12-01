<?php

define('DATA_DIR', ROOT_DIR.'/data');
define('LOG_DIR', ROOT_DIR.'/logs');
define('DB_DIR', ROOT_DIR.'/data/db');

//////////////////////////////////////////////////////////////
////////////////////////常用的debug方法///////////////////////
//////////////////////////////////////////////////////////////

function pe($data){
    echo "<pre>";
    print_r($data);
    exit;
}

function de($data){
    echo "<pre>";
    var_dump($data);
    exit;
}

function elog($data, $name=null, $file_name=null){
    error_log(($name?$name:' 日志记录').' @'.date('Y-m-d H:i:s')."".
        var_export($data,1)."\n",3,DATA_DIR.'/'.($file_name?$file_name:date('ymd')).'.log');
}