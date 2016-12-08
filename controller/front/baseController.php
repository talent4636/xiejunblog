<?php
/**
 * Created by PhpStorm.
 * User: xiejun
 * Time: 16/12/1 19:26
 */

class controller_front_baseController extends controller_baseController{

    public function __construct(){
        parent::__construct();
        $this->view->set('site_title','我的blog');
        $this->view->set('nav_bar',[
            [
                'name'=>'我的博客',
                'url' =>'blog',
            ],
            [
                'name'=>'小工具',
                'url' =>'tools',
            ],
            [
                'name'=>'HTML5',
                'url' =>'html5',
            ],
        ]);
    }

    public function index(){
        $this->view->display('front/index.html');
        $this->view->set('view_dir', VIEW_DIR);
    }

    /**
     * @desc:获取时间线博客
     */
    public function blog_timeLine(){
        //
    }
}