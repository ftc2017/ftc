<?php
namespace Admin\Controller;
use Think\Controller;
use think\Page;
use Think\Upload;

class UserController extends AdminController {

	public function _initialize(){
		parent::_initialize();
		layout('Index/layout');
		$this->assign('top_title','user');
	}
	
	public function getExcel($where){
		$body = M('users')->where($where)->select();
		foreach ($body as $k=>$v) {
			$body[$k]['parent'] = M('users')->where(array('user_id'=>$v['user_id']))->getField('user_name');
			$body[$k]['time_now'] = date('Y-m-d',$v['reg_time']);
		}
		$head = array('A1'=>'用户名','B1'=>'真实姓名','C1'=>'手机号','D1'=>'推荐人','E1'=>'可用余额','F1'=>'冻结金额','G1'=>'注册时间');
		$format = array('A'=>'user_name','B'=>'real_name','C'=>'mobile','D'=>'parent','E'=>'user_money','F'=>'frozen_money','G'=>'time_now');
		exportXLS("会员列表", $head, $body,$format);
	}
	
	public function getParentExcel($body){
		foreach ($body as $k=>$v) {
			$body[$k]['parent'] = M('users')->where(array('user_id'=>$v['referees']))->getField('user_name');
			$body[$k]['num'] = $v['num'] - 1;
		}
		$head = array('A1'=>'推广人','B1'=>'投资人','C1'=>'投资金额','D1'=>'投资笔数');
		$format = array('A'=>'parent','B'=>'user_name','C'=>'re_m','D'=>'num');
		exportXLS("投资记录", $head, $body,$format);
	}
	
	//会员
	public function index()
	{
		// $where['is_lock'] = 0;
		$data = I('get.');
		if($data['start_time'] && $data['end_time']){
			$where['reg_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['type']==1 && $data['status']==1) {
			$where['user_money'] = array('egt',$data['money']);
		}
		if ($data['type']==1 && $data['status']==2) {
			$where['user_money'] = array('eq',$data['money']);
		}
		if ($data['type']==1 && $data['status']==3) {
			$where['user_money'] = array('elt',$data['money']);
		}
		if ($data['type']==2 && $data['status']==1) {
			$where['frozen_money'] = array('egt',$data['money']);
		}
		if ($data['type']==2 && $data['status']==2) {
			$where['frozen_money'] = array('eq',$data['money']);
		}
		if ($data['type']==2 && $data['status']==3) {
			$where['frozen_money'] = array('elt',$data['money']);
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		if (I('excel')) {
			$this->getExcel($where);
		}
		$list =$this->getList('users','',$where,'user_id desc','','');
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	public function balance_change()
	{
		 $user_id = I('get.id');
        if(!$user_id > 0)
            $this->error("参数有误");
        if(IS_POST){
            //获取操作类型
            $m_op_type = I('post.money_act_type');
            $user_money = I('post.user_money');
            $user_money =  $m_op_type ? $user_money : 0-$user_money;

            $f_op_type = I('post.frozen_act_type');
            $frozen_money = I('post.frozen_money');
            $frozen_money =  $f_op_type ? $frozen_money : 0-$frozen_money;

            $desc = I('post.desc');
            if(!$desc)
                $this->error("请填写操作说明");
            if(accountLog($user_id,$user_money,$frozen_money,$desc,0,4)){
            	$user = M('users')->where(array('user_id'=>$user_id))->find();
            	if ($m_op_type) {
            		$all_money = $user['user_money']+$user['frozen_money'];
            		sendSMS($user['mobile'],'您好，您的平台账户增加'.$user_money.'元，目前总额'.$all_money.'元，可用余额'.$user['user_money'].'元，增加原因'.$desc.'，请登录平台查看具体信息。');
            	}
                $this->success("操作成功",U("Admin/User/account_log",array('id'=>$user_id)));
            }else{
                $this->error("操作失败");
            }
            exit;
        }
        $this->assign('user_id',$user_id);
		$this->display();
	}


    /**
     * 账户资金记录
     */
    public function account_log(){
        $user_id = I('get.id');
        //获取类型
        $type = I('get.type');
        //获取记录总数
        $count = M('account_log')->where(array('user_id'=>$user_id))->count();
        $page = new \Think\Page($count);
        $lists  = M('account_log')->where(array('user_id'=>$user_id))->order('change_time desc')->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('user_id',$user_id);
        $this->assign('page',$page->show());
        $this->assign('lists',$lists);
        $this->display();
    }

    public function user_edit()
    {
    	if (IS_POST) {
    		$data=I('post.');
    		if(empty($data['password'])){
	    		unset($data['admin_password']);
	    	}else{
	    		if ($data['password']!=$data['repassword']) {
	    			$this->error("密码不一致");
	    		}else{
	    			$data['password'] = encrypt($data['password']);
	    		}
	    	}
	    	if(empty($data['paypwd'])){
	    		unset($data['paypwd']);
	    	}else{
	    		if ($data['paypwd']!=$data['repaypwd']) {
	    			$this->error("支付密码不一致");
	    		}else{
	    			$data['paypwd'] = encrypt($data['paypwd']);
	    		}
	    	}
	    	if ($data['birthday']) {
	    		$data['birthday'] = strtotime($data['birthday']);
	    	}
    		$sta=M('users')->where(array('user_id'=>$data['user_id']))->save($data);
    		$this->success("操作成功",U("Admin/User/index"));
    	}
    	$id = I('get.id');
    	$res = M('users')->where(array('user_id'=>$id))->find();
    	$this->assign('res',$res);
    	$this->assign('bank',C('bank_array'));
    	$this->display();
    }

    /**
     * 删除会员
     */
    public function delete(){
        $uid = I('id');
        $row = M('users')->where(array('user_id'=>array('in',$uid)))->save(array('users_is_del'=>1));
        if($row){
            $this->success('成功删除会员',U("Admin/User/index"));
        }else{
            $this->error('操作失败');
        }
    }

	//投资记录
	public function investment_log()
	{
		$data = I('get.');
		if ($data['del']==3) {
				$sta = M('users')->where(array('user_id'=>array('in',$data['id'])))->save(array('users_is_del'=>1));
				$this->ajaxReturn(array('status'=>1));
			}
		if($data['start_time'] && $data['end_time']){
			$where['reg_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		// if ($data['tkeyword']) {
		// 	$map['real_name']  = array('like', '%'.$data[tkeyword].'%');
		// 	$map['user_name']  = array('like','%'.$data[tkeyword].'%');
		// 	$map['_logic'] = 'or';
		// 	$wheresql['_complex'] = $map;
		// 	$tou = M('users')->where($wheresql)->select();
		// 	if ($tou) {
		// 		$t_id =array_columns($tou,'user_id');
		// 	}
		// }
		// if ($data['keyword']) {
		// 	$map['real_name']  = array('like', '%'.$data[keyword].'%');
		// 	$map['user_name']  = array('like','%'.$data[keyword].'%');
		// 	$map['_logic'] = 'or';
		// 	$where['_complex'] = $map;
		// }
		// if ($t_id) {
		// 	$where['user_id'] = array('in',$t_id);
		// }
		$where['referees'] = $data['id'];
		$where['users_is_del'] = 0;
		$Model = M('users');
		$count = $Model->where($where)->count();
        $page = new \Think\Page($count);
		$list =$Model->field('cs_users.*,SUM(CASE WHEN cs_account_log.type = 3 THEN cs_account_log.frozen_money ELSE 0 END) as re_m,count(DISTINCT CASE WHEN cs_account_log.type = 3 THEN log_id ELSE 0 END) as num')
		->join('left join cs_account_log ON cs_users.user_id = cs_account_log.user_id')
		->where($where)
		->group('user_id')
		->limit($page->firstRow.','.$page->listRows)
		->select();
		if (I('excel')) {
			$this->getParentExcel($list);
		}
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	//推广管理
	public function promote()
	{
		$data =I('post.');
		if ($data['tkeyword']) {
			$map['real_name']  = array('like', '%'.$data[tkeyword].'%');
			$map['user_name']  = array('like','%'.$data[tkeyword].'%');
			$map['_logic'] = 'or';
			$wheresql['_complex'] = $map;
			$tou = M('users')->where($wheresql)->select();
			if ($tou) {
				$t_id =array_columns($tou,'user_id');
			}
			$wheret['user_id'] = array('in',$t_id);
		}else{
			$wheret['referees'] != 0;
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$wheret['_complex'] = $map;
		}
		//第一个tab
		// $where['referees'] =0;
		// $firstlist =$this->getList('users','',$where);
		//第三个tab
		// $thirhlist =$this->getList('users','',$wheret);
		//第二个tab
		$Model = M('users');
		$count = $Model->where($wheres)->count();
        $page = new \Think\Page($count);
		$list =$Model->field('cs_users.*,count(referees) as tjc')
		->where($wheret)
		->group('referees')
		->limit($page->firstRow.','.$page->listRows)
		->select();
		$list_id = array_columns($list,'referees');
		if ($list_id) {
			$scendlist = M('users')->where(array('user_id'=>array('in',$list_id),'users_is_del'=>0))->select();
		}
		//第一个tab
		// $this->assign('list',$firstlist['list']);
		// $this->assign('page',$firstlist['page']);
		//第三个tab
		// $this->assign('list1',$thirhlist['list']);
		// $this->assign('page1',$thirhlist['page']);
		//第二个tab
		$this->assign('list2',$scendlist);
		$this->assign('page2',$page->show());
		$this->display();
	}

	public function change_promote()
	{
		$data= I('get.');
		$sta =M('users')->where(array('mobile'=>$data['phone']))->find();
		if ($sta) {
			$status = M('users')->where(array('user_id'=>$data['id']))->save(array('referees'=>$sta['user_id']));
			$this->ajaxReturn(array('status'=>1));
		}else{
			$this->ajaxReturn(array('status'=>0,'msg'=>'手机号不存在'));
		}

	}
	//手机认证管理
	
	public function certification_mobile()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['reg_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['type']==1 && $data['status']==1) {
			$where['user_money'] = array('egt',$data['money']);
		}
		if ($data['type']==1 && $data['status']==2) {
			$where['user_money'] = array('eq',$data['money']);
		}
		if ($data['type']==1 && $data['status']==3) {
			$where['user_money'] = array('elt',$data['money']);
		}
		if ($data['type']==2 && $data['status']==1) {
			$where['frozen_money'] = array('egt',$data['money']);
		}
		if ($data['type']==2 && $data['status']==2) {
			$where['frozen_money'] = array('eq',$data['money']);
		}
		if ($data['type']==2 && $data['status']==3) {
			$where['frozen_money'] = array('elt',$data['money']);
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$list = $this->getList('users','',$where);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	public function mobile_pass()
	{
		$data=I('post.');
		if ($data['id'] && $data['status']) {
			$sta = M('users')->where(array('user_id'=>$data['id']))->save(array('mobile_validated'=>$data['status']));
			$this->ajaxReturn(array('status'=>1));
		}
	}

	//认证申请管理
	
	public function certification_apply()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['id_card_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['is_id_card']) {
			$where['is_id_card'] = $data['is_id_card'];
		}
		if ($data['keyword']) {
			$map['real_name']  = array('like', '%'.$data[keyword].'%');
			$map['user_name']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['is_id_card'] = 0;
		$list = $this->getList('users','',$where);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}

	public function apply_pass()
	{
		$data=I('post.');
		if ($data['id']) {
			$sta = M('users')->where(array('user_id'=>$data['id']))->save(array('is_id_card'=>$data['status']));
			$this->ajaxReturn(array('status'=>1));
		}
	}
}
