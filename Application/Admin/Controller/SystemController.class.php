<?php
namespace Admin\Controller;
use Think\Controller;
use Think\page;

class SystemController extends AdminController {

	public function _initialize(){
		parent::_initialize();
		layout('Index/layout');
		// $this->assign('top_title','system');
	}

	public function index()
	{
		$list = array();
		$keywords = I('keywords');
    	if(empty($keywords)){
			$res = $this->getList('admin','','','','','');
    	}else{
			$res = $this->getList('admin','',array('admin_name'=>array('like'=>'%'.$keywords.'%')),'admin_id','','');
    	}
		$role = M('admin_role')->getField('role_id,role_name');	
    	if($res && $role){
    		foreach ($res['list'] as $val){
    			$val['role'] =  $role[$val['role_id']];
    			$val['admin_time'] = date('Y-m-d H:i:s',$val['admin_time']);
    			$list[] = $val;
    		}
    	}
		$this->assign('list',$list);
		$this->assign('page',$res['page']);
		$this->assign('title','index');
		$this->display();
	}

	public function admin_add()
	{
		if (IS_POST) {
			$data = I('post.');
	    	if(empty($data['admin_password'])){
	    		unset($data['admin_password']);
	    	}else{
	    		$data['admin_password'] = encrypt($data['admin_password']);
	    	}
	    	if($data['act'] == 'add'){
	    		unset($data['admin_id']);    		
	    		$data['admin_time'] = time();
	    		if(M('admin')->where(array('admin_name'=>$data['admin_name'],'admin_is_del'=>0))->count()){
	    			$this->error("此用户名已被注册，请更换",U('Admin/System/admin_info'));
	    		}else{
	    			$r = M('admin')->add($data);
	    		}
	    	}
	    	
	    	if($data['act'] == 'edit'){
	    		$r = M('admin')->where(array('admin_id'=>$data['admin_id']))->save($data);
	    	}
	    	
	        if($data['act'] == 'del' && $data['admin_id']>1){
	    		$r = M('admin')->where(array('admin_id'=>$data['admin_id']))->save(array('admin_is_del'=>1));
	    		exit(json_encode(1));
	    	}
	    	
	    	if($r){
	    		$this->success("操作成功",U('Admin/system/index'));
	    	}else{
	    		$this->error("操作失败");
	    	}
		}
		$admin_id = I('get.admin_id');
		if ($admin_id) {
			$admin_user = M('admin')->where(array('admin_id'=>$admin_id))->find();
			$this->assign('info',$admin_user);
		}
		$act = empty($admin_id) ? 'add' : 'edit';
    	$this->assign('act',$act);
    	$role = M('admin_role')->select();
    	$this->assign('role',$role);
		$this->display();	
	}

	public function role(){
    	$list = M('admin_role')->order('role_id desc')->select();
    	$this->assign('list',$list);
    	return $this->display();
    }
    
    public function role_info(){
    	$role_id = I('get.role_id/d');
    	$detail = array();
    	if($role_id){
    		$detail = M('admin_role')->where(array('role_id'=>$role_id))->find();
    		$detail['act_list'] = explode(',', $detail['act_list']);
    		$this->assign('detail',$detail);
    	}
		$right = M('system_menu')->order('id')->select();
		foreach ($right as $val){
			if(!empty($detail)){
				$val['enable'] = in_array($val['id'], $detail['act_list']);
			}
			$modules[$val['group_name']][] = $val;
		}
		//权限组
		$group = array(
			'raise'=>'众筹管理',
			'money'=>'资金统计',
			'user'=>'会员管理',
			'Borrower'=>'借款人管理',
			'recharge'=>'充值管理',
			'Article'=>'文章管理',
			'system'=>'系统设置',
			'Operation'=>'运营管理',
			'Product'=>'商品管理',
			'Packet' =>'红包管理',

		);
		$this->assign('group',$group);
		$this->assign('modules',$modules);
    	return $this->display();
    }
    
    public function roleSave(){
    	$data = I('post.');
    	$res = $data['data'];
    	$res['act_list'] = is_array($data['right']) ? implode(',', $data['right']) : '';
    	if(empty($data['role_id'])){
    		$r = M('admin_role')->add($res);
    	}else{
    		$r = M('admin_role')->where(array('role_id'=>$data['role_id']))->save($res);
    	}
		if($r){
			adminLog('管理角色');
			$this->success("操作成功!",U('Admin/System/role'));
		}else{
			$this->error("操作失败!");
		}
    }
    
    public function roleDel(){
    	$role_id = I('post.role_id/d');
    	$admin = D('admin')->where(array('role_id'=>$role_id))->find();
    	if($admin){
    		exit(json_encode("请先清空所属该角色的管理员"));
    	}else{
    		$d = M('admin_role')->where(array('role_id'=>$role_id))->save(array('role_is_del'=>1));
    		if($d){
    			exit(json_encode(1));
    		}else{
    			exit(json_encode("删除失败"));
    		}
    	}
    }
    
    public function admin_log(){
    	$p = I('p/d',1);
    	$logs = M('admin_log')->order('log_time DESC')->page($p.',20')->select();
    	$this->assign('list',$logs);
    	$count = M('admin_log')->count();
    	$Page = new \Think\Page($count,20);
    	$show = $Page->show();
		$this->assign('pager',$Page);
		$this->assign('page',$show);
    	$this->display();
    }

    public function website_add()
    {
    	if (IS_POST) {
    		$data = I('post.');
    		if ($data['website_id']) {
    			$sta = M('website')->where(array('website_id'=>$data['website_id']))->save($data);
    		}else{
    			$sta = M('website')->add($data);
    		}
    		$this->success('成功');exit();
    	}else{
            $info =M('website')->find();
            $this->assign('info',$info);
            $this->display();
        }
    	
    }

    //banner链接
    public function banner_list()
    {
        $keywords = I('keywords');
        if ($keywords) {
            $map['banner_name']  = array('like', '%'.$data[keyword].'%');
            $map['banner_address']  = array('like','%'.$data[keyword].'%');
            $map['_logic'] = 'or';
            $where['_complex'] = $map;
        }
        $res = $this->getList('banner','',$where,'banner_sort','','');
        $this->assign('list',$res['list']);
        $this->assign('page',$res['page']);
        $this->display();
    }
    //banner链接
    public function banner_add()
    {
        if (IS_POST) {
            $data = I('post.');
            if ($data['act']) {
                $sta = M('banner')->where(array('banner_id'=>$data['id']))->save(array('banner_is_del'=>1));
                $this->ajaxReturn(array('status'=>1));
            }
            if ($data['banner_id']) {
                $sta = M('banner')->where(array('banner_id'=>$data['banner_id']))->save($data);
            }else{
                $data['banner_time'] = time();
                $sta = M('banner')->add($data);
            }
            $this->success('成功');
        }
        if (I('banner_id')) {
            $info = M('banner')->where(array('banner_id'=>I('banner_id')))->find();
            $this->assign('info',$info);
        }
        $this->display();
    }

    public function modify_pwd()
    {
    	// dump($_SESSION);die;
    	if (IS_POST) {
    		$data = I('post.');
    		if (encrypt($data['old_password']) != $_SESSION['admin_password']) {
    			$this->error('原密码错误');
    		}
    		if ($data['admin_password'] != $data['re_password']) {
    			$this->error('确认密码错误');
    		}
    		$pass = encrypt($data['admin_password']);
    		$sta = M('admin')->where(array('admin_id'=>$_SESSION['admin_id']))->save(array('admin_password'=>$pass));
    		$this->success('成功');
    	}
    	$this->display();
    }
    
    public function systemDel(){
    	M('admin')->where(array('admin_id'=>array('in',I('id'))))->setField('admin_is_del',1)||$this->error('操作失败');
    	$this->success('操作成功');
    }
}