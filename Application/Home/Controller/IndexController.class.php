<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
	public function _initialize(){
		parent::_initialize();
    }
	public function index(){
			$this->display();
	}
	public function workspace()
	{
		$this->display();
	}
}