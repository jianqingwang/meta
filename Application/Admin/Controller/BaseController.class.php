<?php
namespace Admin\Controller;

use Think\Controller;

class BaseController extends Controller {

    protected function _initialize(){
        defined('APP_DEMO') || define('APP_DEMO', 0);

        if($_SESSION['is_login']!='admin' && !in_array(CONTROLLER_NAME,['Login'])){
            redirect(U('Admin/Login/index'));
        }
    }







}
?>