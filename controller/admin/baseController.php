<?php

class controller_admin_baseController extends controller_baseController{

    public function __construct(){
        parent::__construct();
        if(!self::checkAdmin()){
            self::login();
        }
    }

    public function checkAdmin(){
        return true;
    }

    public function login(){
        //TODO
    }

    public function post_login(){
        //TODO
    }

    public function index(){
        $db = kernel::dataFile();
        $blogId = $db->fetch('base','content.ids');
        $blog = array();
        if(!empty($blogId)){
            foreach($blogId as $id){
                $blog[] = $db->fetch('content',$id);
            }
        }
        $this->view->set('blog',$blog);
        $this->view->display('admin/index.html');
    }

    public function blog(){
        $this->view->display('admin/blog.html');
    }

    public function blog_detail($id){
        $blog = kernel::dataFile()->fetch('content',$id);
        $this->view->set('blog', $blog);
        $this->view->display('admin/blog/detail.html');
    }

    public function blog_new(){
        $this->view->display('admin/blog/detail.html');
    }

    public function blog_save(){
        $db = kernel::dataFile();
        if($_POST['id']){
            $db->store('content',$_POST['id'], $_POST);
        }else{
            $id = md5(time().$_POST['title']);
            $_POST['id'] = $id;
            $db->store('content',$id,$_POST);
            $markIds = 'content.ids';
            $old_ids = $db->fetch('base',$markIds);
            if(empty($old_ids)){
                $old_ids = array($id);
            }else{
                array_push($old_ids, $id);
            }
            $db->store('base', $markIds, $old_ids);
        }
        header('Locate:/index.php/admin');
    }

    public function blog_delete($id){
        //
    }
}