<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
//资金管理
class RechargeController extends AdminController {
	public function _initialize(){
		parent::_initialize();
	}
	
	//会员账户
	public function index()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['recharge_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['type']) {
			$where['recharge_status'] = $data['type'];
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['recharge_type'] = 1;
		$join[] ='left join cs_users on cs_recharge.user_id = cs_users.user_id'; 
		$list =$this->getListforjoin('recharge','',$join,$where,'recharge_id','','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	// array(6) {
	//   ["action"] => string(4) "send"
	//   ["userid"] => int(4722)
	//   ["account"] => string(2) "zc"
	//   ["password"] => string(9) "zczc_2017"
	//   ["mobile"] => string(11) "18649161833"
	//   ["content"] => string(180) "【众创网络】您好，您申请充值5000.00元，审核失败，失败原因buzhidao。如有疑问请致电：022-83281721或联系专属客户经理，谢谢您的理解。"
	// }
	//修改充值状态
	public function change_status()
	{
		$id = I('id');
		$status = I('status');
		$recharge_note =I('recharge_note');
		if ($id && $status) {
			$sta = M('recharge')->where(array('recharge_id'=>$id))->save(array('recharge_status'=>$status,'recharge_note'=>$recharge_note));
			$recharge = M('recharge')->where(array('recharge_id'=>$id))->find();
			if ($sta && $status==2) {
				accountLog($recharge['user_id'], $recharge['recharge_money'],0,'充值',0,1);
				$user = M('users')->where(array('user_id'=>$recharge['user_id']))->find();
				if ($user['mobile']) {
					$all_money = $user['user_money']+$user['frozen_money'];
					sendSMS($user['mobile'],'您好，您的平台账户充值'.$recharge['recharge_money'].'元，目前总额'.$all_money.'元，可用余额'.$user['user_money'].'元，请登录平台查看最新项目信息，感谢您的信任与支持。');
				}
				$this->ajaxReturn(array('status'=>1));
			}elseif ($sta && $status==4) {
				$user = M('users')->where(array('user_id'=>$recharge['user_id']))->find();
				sendSMS($user['mobile'],'您好，您申请充值'.$recharge['recharge_money'].'元，审核失败，失败原因'.$recharge_note.'。如有疑问请致电：022-83281721或联系专属客户经理，谢谢您的理解。');
				$this->ajaxReturn(array('status'=>1));
			}
		}
		$this->ajaxReturn(array('status'=>1));
	}
	//线下充值
	public function offline()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['recharge_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['type']) {
			$where['recharge_status'] = $data['type'];
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['recharge_type'] = 2;
		$join[] ='left join cs_users on cs_recharge.user_id = cs_users.user_id'; 
		$list =$this->getListforjoin('recharge','',$join,$where,'recharge_id','','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	//充值记录
	public function recharge_list()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['recharge_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['type']) {
			$where['recharge_status'] = $data['type'];
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['recharge_is_del'] = 0;
		//充值成功
		$recharge_success = M('recharge')->where(array('recharge_status'=>2,'recharge_is_del'=>0))->getField('sum(recharge_money)');
		//充值失败
		$recharge_f = M('recharge')->where(array('recharge_status'=>array('in','3,4'),'recharge_is_del'=>0))->getField('sum(recharge_money)');
		//充值中
		$recharge_zhong = M('recharge')->where(array('recharge_status'=>1,'recharge_is_del'=>0))->getField('sum(recharge_money)');
		$join[] ='left join cs_users on cs_recharge.user_id = cs_users.user_id'; 
		$list =$this->getListforjoin('recharge','',$join,$where,'recharge_id','','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->assign('recharge_success',$recharge_success);
		$this->assign('recharge_f',$recharge_f);
		$this->assign('recharge_zhong',$recharge_zhong);
		$this->display();
	}
	//待审
	public function withdrawal_pending()
	{
		$this->_pub(1);
		$this->display();
	}
	public function _pub($status)
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['withdrawal_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['status']==1 && $data['money']) {
			$where['withdrawal_money'] = array('egt',$data['money']);
		}
		if($data['status']==2 && $data['money']){
			$where['withdrawal_money'] = array('eq',$data['money']);
		}
		if ($data['status']==3 && $data['money']) {
			$where['withdrawal_money'] = array('ELT',$data['money']);
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		if ($status) {
			$where['withdrawal_status'] = $status;
		}
		$join[] ='left join cs_users on cs_withdrawal.user_id = cs_users.user_id'; 
		$join[] ='left join cs_withdrawal_examine on cs_withdrawal.examine_id = cs_withdrawal_examine.examine_id';
		$join[] ='left join cs_admin on cs_withdrawal_examine.admin_id = cs_admin.admin_id';
		$list =$this->getListforjoin('withdrawal','',$join,$where,'cs_withdrawal.withdrawal_id','cs_withdrawal.*,cs_admin.admin_name,cs_users.*,cs_withdrawal_examine.examine_desc,cs_withdrawal_examine.examine_time','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
	}
	//处理中
	public function withdrawal_processed()
	{
		$this->_pub(2);
		$this->display();
	}
	//已提现
	public function have_withdrawal()
	{
		$this->_pub(3);
		$this->display();
	}
	//未通过
	public function not_pass()
	{
		$this->_pub(4);
		$this->display();
	}
	//全部
	public function apply_for_withdrawal()
	{	
		//充值成功
		$withdrawal_success = M('withdrawal')->where(array('withdrawal_status'=>3,'withdrawal_is_del'=>0))->getField('sum(withdrawal_money)');
		//充值中
		$withdrawal_z = M('withdrawal')->where(array('withdrawal_status'=>array('in','1,2'),'withdrawal_is_del'=>0))->getField('sum(withdrawal_money)');
		//失败
		$withdrawal_f = M('withdrawal')->where(array('withdrawal_status'=>4,'withdrawal_is_del'=>0))->getField('sum(withdrawal_money)');
		$this->assign('withdrawal_success',$withdrawal_success);
		$this->assign('withdrawal_z',$withdrawal_z);
		$this->assign('withdrawal_f',$withdrawal_f);
		$this->_pub();
		$this->display();
	}
	//提现审核
	public function examine()
	{	
		$id = I('id');
		$join[] = 'left join cs_users on cs_withdrawal.user_id = cs_users.user_id';
		$res = M('withdrawal')->join($join)->where(array('withdrawal_id'=>$id))->find();
		if (IS_POST) {
			//记录处理人
			$data = I('post.');
			if ($data['status']==2) {
				if ($res['user_money']< $res['withdrawal_money']) {
					$this->error('余额不足');
				}
			}
			$user=M('users')->where(array('user_id'=>$data['user_id']))->find();
			$data['examine_time'] = time();
			$data['admin_id'] = $_SESSION['admin_id'];
			$data['withdrawal_id'] = $id;
			$sta = M('withdrawal_examine')->add($data);
			if ($sta) {
				//修改状态等待到账
				$with[withdrawal_status] = $data['status'];
				$with[examine_id] = $sta;
				if ($data['withdrawal_poundage']) {
					$with['withdrawal_poundage'] = $data['withdrawal_poundage'];
					// $with['withdrawal_actual'] = $res['withdrawal_money']-$data['withdrawal_poundage'];
				}
				$whit = M('withdrawal')->where(array('withdrawal_id'=>$id))->save($with);
				if ($whit) {
					if ($data[status]==2) {
							// $usermoney = M('users')->where(array('user_id'=>$data['user_id']))->setDec('user_money',$res['withdrawal_money']);
							// M('users')->where(array('user_id'=>$data['user_id']))->setInc('frozen_money',$res['withdrawal_money']);
							$user_info=M('users')->where(array('user_id'=>$data['user_id']))->find();
							$all_money = $user_info['user_money']+$user_info['frozen_money'];
							$status = sendSMS($user['mobile'],'您好，您申请提现'.$res['withdrawal_money'].'元，已经通过平台工作人员审核并下发，请关注银行账户信息；目前平台总金额'.$all_money.'元，可用余额'.$user_info['user_money'].'元。');
							$this->success('成功',U('withdrawal_pending'));
					}else{
						$usermoney = M('users')->where(array('user_id'=>$data['user_id']))->setInc('user_money',$res['withdrawal_money']);
						M('users')->where(array('user_id'=>$data['user_id']))->setDec('frozen_money',$res['withdrawal_money']);
						sendSMS($user['mobile'],'您好，您申请提现'.$res['withdrawal_money'].'元交易失败，失败原因'.$data['examine_desc'].'。如有疑问请致电：022-83281721或联系专属客户经理，谢谢您的理解。');
						$this->success('成功',U('not_pass'));
					}
				}else{
					$this->error('修改失败');
				}
			}else{
				$this->error('失败');
			}
		}
		//真正到账修改冻结金额，记录资金log
		if (IS_AJAX) {
			$status = I('status');
			if ($status==3) {
				$whit = M('withdrawal')->where(array('withdrawal_id'=>$id))->save(array('withdrawal_status'=>$status));
				accountLog($res['user_id'], 0,'-'.$res['withdrawal_money'],'提现',0,2);
				$this->ajaxReturn(array('status'=>1));
			}
		}
		$this->assign('res',$res);
		$this->display();
	}
}