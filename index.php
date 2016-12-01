<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/11/26 18:22
 */

define("ROOT_DIR", realpath(dirname(__FILE__)));
require_once ROOT_DIR.'/lib/kernel.php';

kernel::init();

$db = kernel::dataFile();
$arr = [
    '1111'=>'aaaa',
    '222'=>'bbbb',
    '3333'=>'cccc',
];
$db->store('test_dir','test_file',$arr);
$db->fetch('test_dir','test_file',$value);
pe($value);