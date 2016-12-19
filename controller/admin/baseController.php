<?php

class controller_admin_baseController extends controller_baseController{

    public function __construct(){
        session_start();
        parent::__construct();
        if($_SERVER['PATH_INFO'] != '/admin-post_login' && !self::checkAdmin()){
            self::login();
        }
    }

    public function checkAdmin(){
        if(isset($_SESSION['admin'])){
            if($_SESSION['admin']==md5('xiejun')){
                return true;
            }
        }
        return false;
    }

    public function login(){
        if(self::checkAdmin()){
            self::index();
            exit;
        }
        $this->view->display('admin/login.html');
        exit;
    }

    public function post_login(){
        $post = $_POST;
        if($post['login_name']==ADMIN_USER && $post['login_password']==ADMIN_PASS){
            $_SESSION['admin'] = md5('xiejun');
            header('Refresh:1; URL='.kernel::get_url().'/index.php/admin');
            exit(self::admin_msg_box('登陆成功'));
        }
        header('Refresh:1; URL='.kernel::get_url().'/index.php/admin-login');
        exit(self::admin_msg_box('请输入正确的用户名密码',false));
    }

    public function index(){
        self::blog();
    }

    public function blog(){
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
//        $this->view->display('admin/blog.html');
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
        header('Refresh:1; URL='.kernel::get_url().'/index.php/admin');
        exit(self::admin_msg_box('保存成功！(1秒后自动跳转)'));
    }

    public function blog_delete($id){
        $db = kernel::dataFile();
        if(!$id){
            return;
        }else{
            $db->remove('content', $id);
            $markIds = 'content.ids';
            $old_ids = $db->fetch('base',$markIds);
            foreach($old_ids as $key => $value){
                if($value == $id || $value=='') continue;
                $newIds[] = $value;
            }
            $db->store('base',$markIds,$newIds);
            header('Refresh:1; URL='.kernel::get_url().'/index.php/admin-blog');
            exit(self::admin_msg_box('删除成功！(1秒后自动跳转)'));
        }
    }

    public static function admin_msg_box($msg, $type=true){
        return '<div style="width:800px;
    margin:0 auto;
    padding:20px 10px;
    background: '.($type?'lightseagreen':'lightcoral').';
    font-size: 16px;
    color: white;"><p>'.$msg.'</p></div>';
    }
}