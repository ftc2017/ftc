<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
//资金管理
class MoneyController extends AdminController {
	public function _initialize(){
		parent::_initialize();
		layout('Index/layout');
	}
	
	public function getExcel($body){
		foreach ($body as $k=>$v) {
			$body[$k]['total'] = $v['user_money'] + $v['frozen_money'] + $v['recycled_money'];
			$body[$k]['chongzhi'] = $v['chongzhi'] ? $v['chongzhi'] : 0;
		}
		$head = array('A1'=>'用户名','B1'=>'真实姓名','C1'=>'手机号','D1'=>'总资产','E1'=>'可用余额','F1'=>'冻结金额','G1'=>'待收本金','H1'=>'待审核提现','I1'=>'处理中提现','J1'=>'累计提现','K1'=>'累计充值');
		$format = array('A'=>'user_name','B'=>'real_name','C'=>'mobile','D'=>'total','E'=>'user_money','F'=>'frozen_money','G'=>'recycled_money','H'=>'daishe','I'=>'chuli','J'=>'tongguo','K'=>'chongzhi');
		exportXLS("会员账户", $head, $body,$format);
	}
	
	//会员账户
	public function user_account()
	{	
		if (IS_GET) {
			$data =I('get.');
			if ($data['del']==3) {
				$sta = M('users')->where(array('user_id'=>array('in',$data['id'])))->save(array('users_is_del'=>1));
				$this->ajaxReturn(array('status'=>1));
			}
			if ($data['type']==1 && $data['status']==1 && $data['money']) {
				$where = 'and user_money >='.$data['money'];
			}
			if ($data['type']==1 && $data['status']==2 && $data['money']) {
				$where = 'and user_money ='.$data['money'];
			}
			if ($data['type']==1 && $data['status']==3 && $data['money']) {
				$where = 'and user_money <='.$data['money'];
			}
			if ($data['type']==2 && $data['status']==1 && $data['money']) {
				$where = 'and frozen_money >='.$data['money'];
			}
			if ($data['type']==2 && $data['status']==2 && $data['money']) {
				$where = 'and frozen_money ='.$data['money'];
			}
			if ($data['type']==2 && $data['status']==3 && $data['money']) {
				$where = 'and frozen_money <='.$data['money'];
			}
			if ($data['type']==3 && $data['status']==1 && $data['money']) {
				$where = 'and recycled_money >='.$data['money'];
			}
			if ($data['type']==3 && $data['status']==2 && $data['money']) {
				$where = 'and recycled_money ='.$data['money'];
			}
			if ($data['type']==3 && $data['status']==3 && $data['money']) {
				$where = 'and recycled_money <='.$data['money'];
			}
			if ($data['keyword']) {
				$key ='AND (user_name LIKE "%'.$data['keyword'].'%" OR real_name LIKE "%'.$data['keyword'].'%" OR mobile LIKE "%'.$data['keyword'].'%")';
			}
			
		}
		$wheresql = 'users_is_del = 0 '.$where.' '.$key;
		
		
		$Model = M('users');
		$page =0;
		$limit = 10;
		$sql="SELECT a.*,SUM(recharge_money) AS chongzhi,SUM(withdrawal_poundage) AS shouxu,SUM(CASE WHEN b.withdrawal_status = 1 THEN withdrawal_money ELSE 0 END) AS 'daishe', SUM(CASE WHEN b.withdrawal_status = 2 THEN withdrawal_money ELSE 0 END) AS 'chuli', SUM(CASE WHEN b.withdrawal_status = 3 THEN withdrawal_money ELSE 0 END) AS 'tongguo'
				FROM cs_users a
				LEFT JOIN `cs_withdrawal` b ON a.user_id = b.user_id
				LEFT JOIN `cs_recharge` c ON a.user_id = c.user_id
				where users_is_del = 0 ".$where."".$key."  GROUP BY user_id order by reg_time desc limit ".$page.",".$limit;

		$list =$Model->query($sql);
		if (I('excel')){
			$this->getExcel($list);
		}
		$this->assign('list',$list);
		$this->display();
	}
	//充值
	public function recharge()
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
		$join[] ='left join cs_users on cs_recharge.user_id = cs_users.user_id'; 
		$list =$this->getListforjoin('recharge','',$join,$where,'recharge_id desc','','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	//提现
	public function withdrawal()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['withdrawal_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['status']==1) {
			$where['withdrawal_money'] = array('egt',$data['money']);
		}
		if($data['status']==2){
			$where['withdrawal_money'] = array('eq',$data['money']);
		}
		if ($data['status']==3) {
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
		$list =$this->getListforjoin('withdrawal','',$join,$where,'cs_withdrawal.withdrawal_id desc','cs_withdrawal.*,cs_admin.admin_name,cs_users.*,cs_withdrawal_examine.examine_desc,cs_withdrawal_examine.examine_time','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	//资金记录
	public function money_log()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['change_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		$where['users_is_del']=0;
		// $user_id = I('get.id');
        //获取类型
        $type = I('get.type');
        //获取记录总数
        $count = M('account_log')->where($where)->count();
        $page = new \Think\Page($count);
        $lists  = M('account_log')->where($where)->order('change_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $user_money = M('users')->field('SUM(user_money) as user_money')->where(array('users_is_del'=>0))->find();
        $frozen_money = M('users')->field('SUM(frozen_money) as frozen_money')->where(array('users_is_del'=>0))->find();
        $all_money =$user_money['user_money']+$frozen_money['frozen_money'];
        $this->assign('frozen_money',$frozen_money['frozen_money']);
        $this->assign('user_money',$user_money['user_money']);
        $this->assign('all_money',$all_money);
        $this->assign('user_id',$user_id);
        $this->assign('page',$page->show());
        $this->assign('lists',$lists);
        $this->display();
	}
	//网站运营统计
	public function website()
	{
		//会员统计
		$this->member = M('users')->where(array('users_is_del'=>0))->count();
		$this->rz_phone = M('users')->where(array('users_is_del'=>0,'mobile_validated'=>1))->count();
		$this->rz_id_card = M('users')->where(array('users_is_del'=>0,'is_id_card'=>0))->count();
		//资金统计
		$this->xs_chongzhi = M('recharge')->where(array('recharge_type'=>1))->getfield('sum(recharge_money)');
		$this->xx_chongzhi = M('recharge')->where(array('recharge_type'=>2))->getfield('sum(recharge_money)');
		$this->withdrawal_success = M('withdrawal')->where(array('withdrawal_status'=>3))->getfield('sum(withdrawal_money)');
		//收益
		$this->shouyi = M('investment')->where(array('investment_earnings'=>array('neq',0)))->getfield('sum(investment_earnings)');
		//众筹项目
		$this->project_money = M('project')->where(array('project_is_del'=>0))->getfield('sum(project_money)');
		$this->all_project = M('project')->where(array('project_is_del'=>0))->count();
		$this->huan_money = M('project')->where(array('project_is_del'=>0,'project_status'=>5))->getfield('sum(project_money)');
		$this->chenggong = M('project')->where(array('project_is_del'=>0,'project_status'=>5))->count();
		$this->yijia = M('project')->where(array('project_is_del'=>0,'project_status'=>11))->count();
		$this->zaichou = M('project')->where(array('project_is_del'=>0,'project_status'=>2))->count();
		$this->liubiao = M('project')->where(array('project_is_del'=>0,'project_status'=>6))->count();
		$this->daihuikuan = M('project')->where(array('project_is_del'=>0,'project_status'=>4))->count();
		$this->chushouzhong = M('project')->where(array('project_is_del'=>0,'project_status'=>3))->count();
		// $daihuikuan = M('project')->where(array('project_is_del'=>0,'project_status'=>4))->count();
		//平台收益
		// 正常收益:￥71,400.00
		// 收入总计:￥71,400.00
		// 溢价回购支出:￥3,666.66
		// 溢价逾期费用支出：￥3,666.66
		// 支出总计:￥24,226.66
		// 总收益:￥47,173.34
		$this->pingtai = M('platform_earnings')->find();
		//红包统计
		$this->packet = M('packet_users')->join('left join cs_packet on cs_packet_users.packet_id = cs_packet.packet_id')->getfield('sum(packet_facevalue)');
		$this->ke_packet = M('packet_users')->join('left join cs_packet on cs_packet_users.packet_id = cs_packet.packet_id')->where(array('p_u_status'=>1))->getfield('sum(packet_facevalue)');
		$this->shi_packet = M('packet_users')->join('left join cs_packet on cs_packet_users.packet_id = cs_packet.packet_id')->where(array('p_u_status'=>2))->getfield('sum(packet_facevalue)');
		$this->guoqi_packet = M('packet_users')->join('left join cs_packet on cs_packet_users.packet_id = cs_packet.packet_id')->where(array('p_u_status'=>3))->getfield('sum(packet_facevalue)');
		$this->display();
	}
}