<?php

class kernel{

    static $single_class;

    static $db_file;

    public function __construct(){
        //
    }

    public function init(){
        try {
            require_once ROOT_DIR.'/config/config.php';
            if (!self::register_autoload()) {
                @require_once(dirname(__FILE__) . '/autoload.php');
            }
            if(file_exists(ROOT_DIR.'/config/router.php')){
                @require_once(ROOT_DIR.'/config/router.php');
            }
            if(file_exists(PLUGIN_DIR . '/smarty/Smarty.class.php')){
                require_once(PLUGIN_DIR . '/smarty/Smarty.class.php');
            }
            //处理原始数据，转化成数组
            self::array_tidy_pool($_GET);
            self::array_tidy_pool($_POST);
            #找到基础路由，分析get参数跳转
            $requestUrl = $_SERVER['REQUEST_URI'];
            if(substr($requestUrl,0,10) == '/index.php'){
                $requestUrl = substr($requestUrl,10);
            }
            $methArr = explode('-',$requestUrl);
            if($methArr[1] == ''){
                $method = 'index';
                $class_name = 'controller_front_baseController';
            }else{
                $method = $methArr[1];
                unset($methArr[0]);
                unset($methArr[1]);
                $requestUrl = substr($requestUrl, 0, strpos($requestUrl,'-'));
            }
            $requestArr = explode('/',$requestUrl);
            if(isset($requestArr[1]) && $requestArr[1]!=''){
                $class_name = $_BASE_ROUTER[$requestArr[1]]['class'];
                if(!$class_name || !class_exists($class_name,true)){
                    exit('System error: wrong url, please try again.[class do not exist]');
                }else{
                    $class = kernel::get_class($class_name);
                    if(!method_exists($class,$method)){
                        exit('System error: wrong url, please try again.[function do not exist]');
                    }
                    call_user_func_array(array($class, $method),$methArr);
                }
            }else{
                call_user_func_array(array(kernel::get_class($class_name), $method),$methArr);
            }
        }catch (Exception $e){
            //TODO 异常处理
            elog($e,'kernel exception','kernel_exception');
        }
    }

    static function get_url(){
        if($_SERVER['SERVER_PORT'] == '443'){
            $https = true;
        }else{
            $https = false;
        }
        $server_url = $_SERVER['HTTP_HOST'];
        return ($https?'https':'http').'://'.$server_url;
    }

    static function dataFile(){
        if(!isset(self::$db_file)){
            self::$db_file = self::get_class('lib_store_file');
        }
        return self::$db_file;
    }

    static function array_tidy_pool(&$var){
        foreach($var as $k=>$v){
            if(is_array($v)){
                self::strip_magic_quotes($var[$k]);
            }else{
                $var[$k] = stripcslashes($v);
            }
        }
    }

    //新的对象
    static function new_class($class_name, $params=null){
        return new $class_name($params);
    }

    //单例的对象，节省开销
    static function get_class($class_name, $params=null){
        if($params===null){
            $md5_mark = md5($class_name);
        }else{
            if(is_object($params)){
                $md5_mark = md5($class_name.get_class($params));
            }elseif(is_array($params)){
                $md5_mark = md5($class_name.json_encode($params));
            }else{
                $md5_mark = md5($class_name.$params);
            }
        }
        if(!self::$single_class[$md5_mark]){
            self::$single_class[$md5_mark] = new $class_name($params);
        }
        return self::$single_class[$md5_mark];
    }

    static function register_autoload($load=array('kernel', 'autoload')){
        if(function_exists('spl_autoload_register')){
            return spl_autoload_register($load);
        }else{
            return false;
        }
    }

    static function unregister_autoload($load=array('kernel', 'autoload')){
        if(function_exists('spl_autoload_register')){
            return spl_autoload_unregister($load);
        }else{
            return false;
        }
    }

    static function autoload($class_name){
        $p = strpos($class_name,'_');
        if($p){
            $param = explode('_',$class_name);
            $num = count((array)$param);
            $path = '';
            for($i=0;$i<$num-1;$i++){
                $path .= ($param[$i].'/');
            }
            $path .= $param[$num-1].'.php';
            if(file_exists(ROOT_DIR.'/'.$path)){
                @require(ROOT_DIR.'/'.$path);
            }
        }
    }

}
