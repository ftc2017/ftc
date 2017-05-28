<?php
namespace Home\Controller;
use Think\Controller;
class ProductController extends BaseController {
	public function _initialize(){
      parent::_initialize();
      $this->assign('title_top','Account');
    }
	/**
	 * 产品列表
	 */
	public function product(){
		// parent::page('product',10,'product','product_label desc,product_id desc',array('product_is_del'=>0));
		
		$this-> display();
		
	}
	
	/**
	 * 产品详情
	 */
	public function product_xq(){
		if (IS_POST) {
			$user = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
			$data = I('post.');
			if ($data['bizhong']==1) {
				if ($user['b_user_money']<$data['money']) {
					$this->ajaxReturn(array('status'=>0,'msg'=>'余额不足'));
				}
			}elseif ($data['bizhong']==2) {
				
				if ($user['r_user_money']<$data['money']) {
					$this->ajaxReturn(array('status'=>0,'msg'=>'余额不足'));
				}
			}elseif ($data['bizhong']==3) {
				
				if ($user['l_user_money']<$data['money']) {
					$this->ajaxReturn(array('status'=>0,'msg'=>'余额不足'));
				}
			}
			$post['account_currency'] = $data['bizhong'];
			$post['raise_money'] = $data['money'];
			$post['investment_time'] = time();
			$post['user_id'] = $_SESSION['user']['user_id'];
			$post['project_id'] = 1;
			M('investment')->add($post);
			if ($data['bizhong']==1) {
				M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->setDec('b_user_money',$data['money']);
			}elseif ($data['bizhong']==2) {
				M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->setDec('r_user_money',$data['money']);
			}elseif ($data['bizhong']==3) {
				M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->setDec('l_user_money',$data['money']);
			}
			$this->ajaxReturn(array('status'=>1,'msg'=>'支持成功'));
		}
		$tian=ceil((1497499200-time())/86400);
		$people = M('investment')->where(array('project_id'=>1))->count();
		$jine = M('investment')->where(array('project_id'=>1))->sum('raise_money');
		$bili = $jine/10000000*100;
		$this->assign('tian',$tian);
		$this->assign('people',$people);
		$this->assign('jine',$jine);
		$this->assign('bili',$bili);
		$this-> display();
		
	}
	
	
	
	
	
	
	
}