<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/11/26 18:25
 */

class lib_store_file{

    public function store($table_name=null,$_key, $value){
        $file = self::key_to_file($table_name,$_key);
        self::file_put_value($file,$value);
        return true;
    }

    public function fetch($table_name=null,$_key, &$value=null){
        $file = self::key_to_file($table_name,$_key);
        $value = self::file_get_value($file);
        return true;
    }

    public function remove($table_name=null,$_key){
        $file = self::key_to_file($table_name,$_key);
        self::del($file);
    }

    private static function key_to_file($table_name,$key){
        if(!is_dir(DB_DIR)){
            @mkdir(DB_DIR);
        }
        if(!is_dir(DB_DIR.'/'.$table_name)){
            @mkdir(DB_DIR.'/'.$table_name);
        }
        if(!$table_name){
            return DB_DIR.'/'.($key).'.log';
        }
        return DB_DIR.'/'.$table_name.'/'.($key).'.log';
    }

    private function file_put_value($file,$value){
        $value = serialize($value);
        if(!file_exists($file)){
            @touch($file);
        }
        $fileHandle = @fopen($file,'w');
        @fwrite($fileHandle, $value);
        @fclose($fileHandle);
    }

    private function file_get_value($file){
        return unserialize(file_get_contents($file));
    }

    private function del($file){
        @unlink($file);
    }

}