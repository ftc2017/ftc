<?php
namespace Home\Controller;
use Think\Controller;
class RechargeController extends BaseController {
	public function _initialize(){
		parent::_initialize();
   		$this->assign('one','会员中心');
   		$this->assign('one_url',U('member/account'));
    }
	//线下充值
	public function index()
	{
		if (IS_POST) {
			$data = I('post.');
			$user = M('users')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
			if ($user['is_id_card'] != '0') {
				$this->ajaxReturn(array('status'=>0,'msg'=>'请前往实名认证再充值'));
			}
			if ($data['recharge_money'] && $data['recharge_statement_no']) {
				$data['recharge_type'] = 2;
				$data['recharge_no'] = 'cz'.time().rand(0,9999);
				$data['user_id'] = $_SESSION['user']['user_id'];
				$data['recharge_status'] = 1;
				$data['recharge_time'] = time();
				$sta = M('recharge')->add($data);
				$this->ajaxReturn(array('status'=>1,'msg'=>'提交成功，请等待后台确认'));
			}
		}
		$user_info = M('users')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		$offbank =M('offbank')->where(array('offbank_id'=>1))->find();
		$this->assign('title','recharge');
		$this->assign('real_name',$user_info['real_name']);
		$this->assign('offbank',$offbank);
		$this->assign('two','充值');
		$this->display();
	}

	public function ajax_verify()
	{
		$user = M('users')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		if (I('type')==1) {
			if (!$user['bank_card']) {
				$this->ajaxReturn(array('status'=>0,'type'=>'1','msg'=>'请先前往绑定银行卡'));
			}
		}
		if ($user['is_id_card'] != '0') {
			$this->ajaxReturn(array('status'=>0,'msg'=>'请先前往实名认证'));
		}else{
			$this->ajaxReturn(array('status'=>1));
		}
	}
	//充值记录
	public function recharge_log()
	{
		if (I('recharge_type')) {
			$recharge_type = I('recharge_type');
		}else{
			$recharge_type = 2;
		}
		if ($recharge_type == 1) {
			$where['recharge_type'] = 1;
		}
		if ($recharge_type == 2) {
			$where['recharge_type'] = 2;
		}
		if (I('starttime') && I('endtime')) {
				$starttime = strtotime(I('starttime'));
				$endtime = strtotime(I('endtime'));
				$where['recharge_time']  = array('between',array($starttime,$endtime));
			}
		$where['recharge_is_del'] = 0;
		$where['user_id'] = $_SESSION['user']['user_id'];
		$join[] ="left join cs_offbank on cs_recharge.offbank_id = cs_offbank.offbank_id";
		parent::page('recharge',5,'list','recharge_id desc',$where,$join);
		$this->assign('title','recharge');
		$this->assign('recharge_type',$recharge_type);
		$this->assign('two','充值');
		$this->display();
	}
	//提现
	public function withdrawal()
	{
		$user = M('users')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		if (IS_POST) {
			$data = I('post.');
			$user = M('users')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
			if ($user['is_id_card'] != '0') {
				$this->ajaxReturn(array('status'=>0,'msg'=>'请前往实名认证再提现'));
			}
			if (!$user['bank_card']) {
				$this->ajaxReturn(array('status'=>0,'msg'=>'请前往绑定银行卡再提现'));
			}
			if ($data['withdrawal_money'] && $data['paypwd']) {
				if (md5($data['paypwd'])!=$user['paypwd']) {
					$this->ajaxReturn(array('status'=>0,'msg'=>'支付密码错误'));
				}
				if ($data['withdrawal_money']>$user['user_money']) {
					$this->ajaxReturn(array('status'=>0,'msg'=>'提现金额超限'));
				}
				//前端只管提交提现，资金log后台记录
				$post['user_id'] = $_SESSION['user']['user_id'];
				$post['withdrawal_money'] = $data['withdrawal_money'];
				$post['withdrawal_time'] = time();
				$post['withdrawal_status'] = 1;
				$sta = M('withdrawal')->add($post);
				$status = accountLog($user['user_id'],'-'.$data['withdrawal_money'],$data['withdrawal_money'],'提现','0','2');
				$this->ajaxReturn(array('status'=>1,'msg'=>'提交成功，请等待后台确认'));
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>'必填项不能为空'));

			}
		}
		$bank_name = C('bank_array');
		foreach ($bank_name as $key => $value) {
			if ($value['id'] == $user['bank_name']) {
				$bank = $value['name'];
			}
		}
		$this->assign('user',$user);
		$this->assign('title','withdrawal');
		$this->assign('bank',$bank);
		$this->assign('two','提现');
		$this->display();
	}
	//提现记录
	public function withdrawal_log()
	{
		if (I('starttime') && I('endtime')) {
				$starttime = strtotime(I('starttime'));
				$endtime = strtotime(I('endtime'));
				$where['withdrawal_time']  = array('between',array($starttime,$endtime));
			}
		$where['withdrawal_is_del'] = 0;
		$where['user_id'] = $_SESSION['user']['user_id'];
		// $join[] ="left join cs_offbank on cs_recharge.offbank_id = cs_offbank.offbank_id";
		parent::page('withdrawal',5,'list','withdrawal_id desc',$where);
		$this->assign('title','withdrawal');
		$this->assign('two','提现');
		$this->display();
	}
}