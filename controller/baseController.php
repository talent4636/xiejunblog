<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/12/1 18:47
 */

class controller_baseController{

    function __construct(){
        //TODO 构造方法
        $this->view = kernel::get_class('lib_view');
        $this->view->set('view_dir', kernel::get_url().'/view');
    }

}