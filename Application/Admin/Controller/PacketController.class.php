<?php
namespace Admin\Controller;
class PacketController extends AdminController {
	//红包管理
	public function lists(){
		$T_packet = M('packet');
		$this->packet_list = parent::pageS($T_packet,12,'packet_id asc','*',array('is_del'=>0));
		$this->display();
	}
	public function edit(){
		$parm = I('get.');
		parent::verify_parm($parm) || $this->error('参数错误。。。');
		if (!empty($parm['id'])) {
			$this->packet_info = M('packet')->where(array('packet_id'=>$parm['id']))->find();
		}
		$this->display();
	}
	public function editok(){
		$parm = I('post.');
		parent::verify_parm($parm) || $this->error('参数错误。。。');
		$T_packet = M('packet');
		$T_packet->create();
		if (empty($parm['id'])) {
			$T_packet->packet_createtime = time();
			$T_packet->add_admin_id = 1;
			$res = $T_packet->add();
		}else{
			$res = $T_packet->where(array('packet_id'=>$parm['id']))->save();
		}
		$res || $this->error('服务器正忙。。。');
		$this->success('操作成功');
	}
	public function editall(){

	}
	public function del(){
		$parm = I('get.');
		parent::verify_parm($parm,array('id'),array('id'=>'/^\d[0-9]{1,11}$/')) || $this->error('参数错误。。。');
		M('packet')->where(array('packet_id'=>$parm['id']))->delete() || $this->error('服务器正忙。。。');
		$this->success('操作成功');
	}
	public function gain(){
		$parm = I('get.');
		parent::verify_parm($parm) || $this->error('参数错误。。。','',true);
		empty($parm['name']) || $user_where['name'] = $parm['name'];
		$user_limit = empty($parm['page'])?0:($parm['page']-1)*12;
		if ($parm['start_time'] && $parm['end_time']) {
			$where['a.reg_time'] = array(array('egt',strtotime($parm['start_time'])),array('elt',strtotime($parm['end_time'])));
		}
		if ($parm['tkeyword']) {
			$where['a.personal_investment_money'] = array('EGT',$parm['tkeyword']);
		}
		if ($parm['keyword']) {
			$where['a.recommended_investment_money'] = array('EGT',$parm['keyword']);
		}
		$users_list = M('users')->alias('a')->field('a.user_id,a.user_name,a.real_name,a.mobile,b.user_name a_user_name,a.user_money,a.frozen_money,a.reg_time')->join('LEFT JOIN cs_users b ON a.referees=b.user_id')->where($where)->where($user_where['name'])->limit($user_limit.',12')->select();
		if (IS_AJAX) {
			$this->ajaxReturn($users_list,'JSON');
		}else{
			$this->users_list = $users_list;
			$this->display();
		}
	}
	public function gainok(){
		$parm = I('post.');
		if ($parm['uids']) {
			$user_mobile = M('users')->where(array('user_id'=>array('in',$parm['uids'])))->getfield('mobile',true);
		}
		parent::verify_parm($parm,array('uids','pid')) || $this->error('参数错误。。。');
		$pac = M('packet')->where(array('packet_id'=>$parm['pid'],'is_del'=>0))->find();
		if (!$pac) {
			$this->error('红包不存在');
		}
		$T_PacketUsers = M('packet_users');
		$user_count = count($parm['uids']);
		$time = time();
		for ($i=0; $i<$user_count; $i++) { 
			$packet_users_add[] = array('user_id'=>$parm['uids'][$i],'packet_id'=>$parm['pid'],'p_u_gaintime'=>$time,'p_u_admin_id'=>$_SESSION['admin_id']);
			if ($i%500 == 0) {
				$T_PacketUsers->addAll($packet_users_add) || $this->error('服务器正忙。。。');
				unset($packet_users_add);
			}
		}
		if (!empty($packet_users_add)) {
			$T_PacketUsers->addAll($packet_users_add) || $this->error('服务器正忙。。。');
		}
		$mobile = implode(',',$user_mobile);
		sendSMS($mobile,'您好，您收到平台赠送红包'.$pac['packet_name'].'，价值'.$pac['packet_facevalue'].'，请登录平台查看具体信息。');
		$this->success('操作成功',U('lists'),true);
	}
	public function record(){
		$parm = I('get.');
		parent::verify_parm($parm) || $this->error('参数错误。。。');
		$T_PacketUsers = M('packet_users');
		$PacketUsers_join[] = 'LEFT JOIN cs_users ON cs_packet_users.user_id = cs_users.user_id';
		$PacketUsers_join[] = 'LEFT JOIN cs_packet ON cs_packet_users.packet_id = cs_packet.packet_id';
		empty($parm['u_name']) || $PacketUsers_where['user_name'] = $parm['u_name'];
		$this->packetUsers_list = parent::pageS($T_PacketUsers,12,'packet_users_id asc','*',$PacketUsers_where,$PacketUsers_join);
		$this->display();
	}
	
	public function packetDel(){
		M('packet')->where(array('packet_id'=>array('in',I('id'))))->setField('is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
}