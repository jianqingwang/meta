<?php
namespace Home\Controller;

class HomeController extends \Think\Controller{
	
	protected function _initialize(){
		defined('APP_DEMO') || define('APP_DEMO', 0);
	}
  	
}
?>