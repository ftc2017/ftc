<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class OperationController extends AdminController {
	public function index()
	{	
		$this->_pub();
		$this->display();
	}
	public function _pub()
	{
		if (IS_POST) {
			$data =I('post.');
			$sta = M('operation')->find();
			if ($sta) {
				$status = M('operation')->where(array('operation_id'=>$sta['operation_id']))->save($data);
				$this->success('成功');
			}else{
				$status = M('operation')->add($data);
				$this->success('成功');
			}
		}
		$info = M('operation')->find();
		$this->assign('info',$info);
	}
	public function operation_rule()
	{
		$this->_pub();
		$this->display();
	}

	public function bizparam()
	{
		$res = $this->getList('bizparam','',$where,'','','');
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->display();
	}

	public function bizparam_add()
	{
		$data = I('post.');
		if ($data['act']) {
				$sta = M('bizparam')->where(array('bizparam_id'=>$data['id']))->save(array('bizparam_is_del'=>1));
				$this->ajaxReturn(array('status'=>1));
			}
		if ($data) {
			foreach ($data['bizparam_name'] as $value) {
				$data['bizparam_time'] = time();
				$data['bizparam_name'] = $value;
			 	$heh = M('bizparam')->add($data);
			}
		}
		$this->success('成功');
	}
	public function contract_parameters()
	{
		if (IS_POST) {
			$data =I('post.');
			$sta = M('contract')->find();
			if ($sta) {
				$status = M('contract')->where(array('contract_id'=>$sta['contract_id']))->save($data);
				$this->success('成功');
			}else{
				$status = M('contract')->add($data);
				$this->success('成功');
			}
		}
		$info = M('contract')->find();
		$this->assign('info',$info);
		$this->display();
	}

	public function offline_bank()
	{
		$res = $this->getList('offbank','',$where,'','','');
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->display();
	}

	public function offline_add()
	{
		$data = I('post.');
		if ($data['act']) {
				$sta = M('offbank')->where(array('offbank_id'=>$data['id']))->save(array('offbank_is_del'=>1));
				$this->ajaxReturn(array('status'=>1));
			}
		$res = array();
		if ($data) {
			$i =0;
			foreach ($data['offbank_name'] as $value) {
				$res[$i]['offbank_name'] = $value;
				$i = $i+1;
			}
			$j =0;
			foreach ($data['offbank_member'] as $value) {
				$res[$j]['offbank_member'] = $value;
				$j = $j+1;
			}
			$k =0;
			foreach ($data['offbank_card'] as $value) {
				$res[$k]['offbank_card'] = $value;
				$k = $k+1;
			}
			$l =0;
			foreach ($data['offbank_address'] as $value) {
				$res[$l]['offbank_address'] = $value;
				$l = $l+1;
			}
		}

		foreach ($res as $key) {
			M('offbank')->add($key);
		}
		$this->success('成功');
	}

	//客服管理
	public function service_qq()
	{
		$keywords = I('keywords');
		if ($keywords) {
			$where['service_title'] = array('like'=>'%'.$keywords.'%');
		}
		$where['service_type'] = 1;
		$res = $this->getList('service','',$where,'service_sort','','');
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->display();
	}

	public function service_add()
	{
		if (IS_POST) {
			$data = I('post.');
			if ($data['act']) {
				$sta = M('service')->where(array('service_id'=>$data['id']))->save(array('service_is_del'=>1));
				$this->ajaxReturn(array('status'=>1));
			}
			if ($data['service_id']) {
				$sta = M('service')->where(array('service_id'=>$data['service_id']))->save($data);
			}else{
				$data['service_time'] = time();
				$sta = M('service')->add($data);
			}
			$this->success('成功');
		}
		$type = I('type');
		if ($type) {
			$this->assign('type',$type);
		}
		$id = I('service_id');
		$info = M('service')->where(array('service_id'=>$id))->find();
		$this->assign('info',$info);
		$this->display();
	}
	public function service_mobile()
	{
		$keywords = I('keywords');
		if ($keywords) {
			$where['service_title'] = array('like'=>'%'.$keywords.'%');
		}
		$where['service_type'] = 2;
		$res = $this->getList('service','',$where,'service_sort','','');
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->display();
	}

	//邀请链接
	public function link_list()
	{
		$keywords = I('keywords');
		if ($keywords) {
			$map['link_name']  = array('like', '%'.$data[keyword].'%');
			$map['link_address']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		if (I('link_is_display')) {
			$where['link_is_display'] = I('link_is_display');
		}
		if (I('link_display_position')) {
			$where['link_display_position'] = I('link_display_position');
		}
		$res = $this->getList('link','',$where,'link_sort','','');
		$this->assign('list',$res['list']);
		$this->assign('page',$res['page']);
		$this->display();
	}
	//邀请链接
	public function link_add()
	{
		if (IS_POST) {
			$data = I('post.');
			if ($data['act']) {
				$sta = M('link')->where(array('link_id'=>$data['id']))->save(array('link_is_del'=>1));
				$this->ajaxReturn(array('status'=>1));
			}
			if ($data['link_id']) {
				$sta = M('link')->where(array('link_id'=>$data['link_id']))->save($data);
			}else{
				$data['link_time'] = time();
				$sta = M('link')->add($data);
			}
			$this->success('成功');
		}
		if (I('link_id')) {
			$info = M('link')->where(array('link_id'=>I('link_id')))->find();
			$this->assign('info',$info);
		}
		$this->display();
	}
	
	public function bizDel(){
		M('bizparam')->where(array('bizparam_id'=>array('in',I('id'))))->setField('bizparam_is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
	
	public function bankDel(){
		M('offbank')->where(array('offbank_id'=>array('in',I('id'))))->setField('offbank_is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
	
	public function mobileDel(){
		M('service')->where(array('service_id'=>array('in',I('id'))))->setField('service_is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
}