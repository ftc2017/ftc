<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class BorrowerController extends AdminController {

	public function _initialize(){
		parent::_initialize();
		layout('Index/layout');
		$this->assign('top_title','borrower');
	}
	//借款人管理
	public function index()
	{	
		if (I('get.keyword')) {
			$k = I('get.keyword');
			$map['borrower_phone'] = array('like','%'.$k.'%');
			$map['borrower_username'] = array('like','%'.$k.'%');
			$map['borrower_name'] = array('like','%'.$k.'%');
			$map['_logic'] = 'OR';
			$where['_complex'] = $map;
		}
		if (I('get.keyword')) {
			$k = I('get.keyword');
			$map['borrower_phone'] = array('like','%'.$k.'%');
			$map['borrower_username'] = array('like','%'.$k.'%');
			$map['borrower_name'] = array('like','%'.$k.'%');
			$map['_logic'] = 'OR';
			$wheresql['_complex'] = $map;
		}
		$where['borrower_type'] =1;
		$wheresql['borrower_type'] =2;
		$geren = $this->getList('borrower','',$where,'','','');
		$cheshang = $this->getList('borrower','',$wheresql,'','','');

		$this->assign('geren',$geren['list']);
		$this->assign('page',$geren['page']);
		$this->assign('cheshang',$cheshang['list']);
		$this->assign('page1',$cheshang['page']);
		// return $this->fetch();
		$this->display();
	}
	//添加借款人
	public function add()
	{

		if (IS_POST) {
			$data = I('post.');
			$data['borrower_time'] = time();
			$sta = M('borrower')->add($data);
			$this->success('操作成功');
		}
		$province = M('region')->where(array('level'=>1))->select();
		$this->assign('province',$province);
		$this->assign('title','index');
		$this->display();
	}

	//获取地址
	public function getChildAjax()
	{
		$id = I('id');
		$dq = M('region')->where(array('parent_id'=>$id))->select();
		$this->ajaxReturn($dq);
	}
	
	public function borrowerDel(){
		M('borrower')->where(array('borrower_id'=>array('in',I('id'))))->setField('borrower_is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
}