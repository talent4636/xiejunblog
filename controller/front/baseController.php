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
        $this->view->set('blog',array_reverse($blog));
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
        $this->view->set('func_list',self::tools_list());
        $this->_set_nav_active(__FUNCTION__);
        $this->view->display('front/tools.html');
    }

    static private function tools_list(){
        return array(
            array('serialize','序列化'),
            array('unserialize','反序列化'),
            array('jsonencode','Json_encode'),
            array('jsondecode','Json_decode'),
            array('unixtime','UNIX时间转化'),
            array('timeunix','时间转化时间戳'),
        );
    }

    public function tools_detail($funcName=null){
        $dataInput = $_POST['data'];
        if($dataInput){
            #$dataType = $_POST['type'];//print_r
            switch($funcName){
                case 'unserialize':
                    $data = unserialize($dataInput);
                    break;
                case 'serialize':
                    eval('$input='.$dataInput.(substr($dataInput,-1,1)==';'?'':';'));
                    $data = serialize($input);
                    break;
                case 'jsonencode':
                    eval('$input='.$dataInput.(substr($dataInput,-1,1)==';'?'':';'));
                    $data = json_encode($input);
                    break;
                case 'jsondecode':
                    $data = json_decode($dataInput);
                    break;
                case 'unixtime':
                    date_default_timezone_set("Asia/Shanghai");//如果没有timezone，时间差8小时
                    $data = date('Y-m-d H:i:s',($dataInput));
                    break;
            }
        }elseif(isset($_POST['y'])){
            $dataInput = array(
                $_POST['y'],
                $_POST['m'],
                $_POST['d'],
                $_POST['h'],
                $_POST['i'],
                $_POST['s'],
            );
            $data = strtotime($dataInput[0].'-'.$dataInput[1].'-'.$dataInput[2].' '.$dataInput[3].':'.$dataInput[4].':'.$dataInput[5]);
        }else{
            $dataInput = null;
        }
        $this->view->set('current_func', $funcName);
        $this->view->set('func_list',self::tools_list());
        $this->view->set('dataInput',$dataInput);
        $this->view->set('dataOutput', print_r($data,1));
        $this->view->display('front/tools_detail_display.html');
    }

    public function html5(){
        $this->_set_nav_active(__FUNCTION__);
        $this->view->display('front/html5.html');
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