<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends AdminController {

	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
	    global $nav;
	    $nav[] = array('name' => '首页');
        $this->assign('admin_info',$admin_info);
        $this->assign('menu',getMenuArr());   
		$this->display();
	}

	public function welcome()
	{
		$raise_num =M('project')->where(array('project_is_del'=>0))->count();
		$ren_num =M('users')->where(array('is_id_card'=>1,'users_is_del'=>0))->count();
		$withdrawal_num =M('withdrawal')->where(array('withdrawal_status'=>1,'withdrawal_is_del'=>0))->count();
		$this->assign('ren_num',$ren_num);
		$this->assign('raise_num',$raise_num);
		$this->assign('withdrawal_num',$withdrawal_num);
		$this->display();
	}
	
	public function gengxin()
	{
	$menuArr = include APP_PATH.'Admin/Conf/menu.php';
		foreach($menuArr as $k=>$val){
			foreach ($val['child'] as $j=>$v){
				foreach ($v['child'] as $s=>$son){
					$data = array(
						'name' => $son['name'],
						'group'=> $son['op'],
						'right'=> $son['op'].'Controller@'.$son['act'],
						);
					M('system_menu')->add($data);
				}
			}
		}
	}
}