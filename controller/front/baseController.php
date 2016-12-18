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
        self::blog();
//        $this->view->display('front/index.html');
    }

    /**
     * @desc:获取时间线博客
     */
    public function blog(){
        $this->_set_nav_active(__FUNCTION__);
        #获取blog数据
        $db = kernel::dataFile();
        $blogId = $db->fetch('base','content.ids');
        $blog = array();
        if(!empty($blogId)){
            foreach($blogId as $id){
                $blog[] = $db->fetch('content',$id);
            }
        }
        $this->view->set('blog',$blog);
        $this->view->display('front/blog.html');
    }

    public function blog_detail($blog_id){
        $this->_set_nav_active('blog');
        $db = kernel::dataFile();
        $blog_detail = $db->fetch('content',$blog_id);;
        $this->view->set('blog',$blog_detail);
        $this->view->display('front/blog_detail.html');
    }

    public function tools(){
        $this->_set_nav_active(__FUNCTION__);
        $this->view->display('front/blog.html');
    }

    public function html5(){
        $this->_set_nav_active(__FUNCTION__);
        $this->view->display('front/blog.html');
    }

    /**
     * @author: xiejun@shopex.cn
     * @desc:设置一个导航标题为选中
     */
    private function _set_nav_active($urlName){
        $nav = $this->view->get('nav_bar');
        foreach($nav as $key => $value){
            if($urlName == $value['url']){
                $nav[$key]['active'] = true;
            }else{
                $nav[$key]['active'] = false;
            }
        }
        $this->view->set('nav_bar',$nav);
    }
}