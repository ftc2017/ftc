<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends BaseController {
	public function _initialize(){
		parent::_initialize();
   		$this->assign('one','会员中心');
   		$this->assign('one_url','account');
    }
	//个人账户首页
	public function setting()
	{
		$user = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		$this->assign('user',$user);
		$this->display();
	}
	public function jczl()
	{
		if (IS_POST) {
			$data = I('post.');
			M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->save(array('user_name'=>$data['user_name']));
			$this->ajaxReturn(array('status'=>1));
		}
		$user = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		$this->assign('user',$user);
		$this->display();
	}
	public function cjzh()
	{
		if (IS_POST) {
			$data = I('post.');
			$data['user_id'] = $_SESSION['user']['user_id'];
			M('member_account')->add($data);
			$this->ajaxReturn(array('status'=>1,'msg'=>'添加成功'));
		}
		$user = M('member_account')->where(array('user_id'=>$_SESSION['user']['user_id']))->select();
		$this->assign('account',$user);
		$this->display();
	}
	//实名认证
	public function index()
	{
		if (IS_POST) {
			$data =I('post.');
			if ($data['id_card'] && $data['real_name']) {
				$res = $this->real_certification($data);
				if ($res['code']==10000) {
					$sta = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->save(array('is_id_card'=>0,'real_name'=>$data['real_name'],'id_card'=>$data['id_card'],'id_card_time'=>time()));
					session('user.is_id_card',0);
					$this->ajaxReturn(array('status'=>1,'msg'=>"认证成功",'url'=>U('member/setting')));
				}else{
					$this->ajaxReturn(array('status'=>0,'msg'=>'认证失败'));
				}
			}
		}
		$this->display();
	}

	public function real_certification($data){
		 	$url="http://api.chinadatapay.com/communication/personal/1859";
			$ch = curl_init();
			  //设置选项，包括URL
			  curl_setopt($ch, CURLOPT_URL, "$url");
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			  curl_setopt($ch, CURLOPT_HEADER, 0);
			  curl_setopt($ch,CURLOPT_TIMEOUT,3); //定义超时3秒钟 
			   // POST数据
			  curl_setopt($ch, CURLOPT_POST, 1);
			  // 把post的变量加上
			  curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					"name"=>$data["real_name"],
					"key"=>"46e3ea0f5c4c34932569a5ba48986d04",
					"idCard"=>$data["id_card"],
					));
			  //执行并获取url地址的内容
			  $output = curl_exec($ch);
			  //释放curl句柄
			  curl_close($ch);
			  return json_decode($output,true);
	}
	//修改登录密码
	public function change_pass()
	{
		if (IS_POST) {
			$data = I('post.');
			if ($data['password'] != $data['repassword'] || !$data['password']) {
				$this->ajaxReturn(array('status'=>0,'msg'=>'两次密码不一致'));
			}
			if (!$data['oldpassword']) {
				$this->ajaxReturn(array('status'=>0,'msg'=>'旧密码不能为空'));
			}
			$user = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
			if (md5($data['oldpassword']) == $user['password']) {
				$sta = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->save(array('password'=>md5($data['password'])));
				// echo M('users')->getlastsql();die;
				$this->ajaxReturn(array('status'=>1,'msg'=>'修改成功','url'=>U('Member/setting')));
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>'旧密码输入错误'));
			}
		}
		$this->assign('two','修改密码');
		$this->assign('title','setting');
		$this->display();
	}



   
	//修改手机号
	public function change_phone()
	{
		if (IS_POST) {
			$data =I('post.');
			if ($data['mobile']) {
				$is_mobile = M('user')->where(array('mobile'=>$data['mobile']))->find();
				if ($is_mobile) {
					$this->ajaxReturn(array('status'=>0,'msg'=>'手机号已存在'));
				}else{
					$sta = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->save(array('mobile'=>$data['mobile']));
				}
			}
			session('user.mobile',$data['mobile']);
			$this->ajaxReturn(array('status'=>1,'msg'=>'修改成功','url'=>U('Member/setting')));
		}
		$user = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		$this->assign('mobile',$user['mobile']);
		$this->assign('two','修改手机号');
		$this->assign('title','setting');
		$this->display();
	}

	public function is_phone()
	{
		$mobile = I('post.mobile');
		$status = M('user')->where(array('mobile'=>$mobile))->find();
		if ($status) {
			$this->ajaxReturn(array('status'=>0));
		}else{
			$this->ajaxReturn(array('status'=>1));
		}
	}
	//修改支付密码
	public function change_pay()
	{
		if (IS_POST) {
			$data =I('post.');
			if ($data['paypwd'] != $data['repaypwd']) {
				$this->ajaxReturn(array('status'=>0,'msg'=>'两次密码不一致'));
			}
			if ($data['paypwd']) {
				$sta = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->save(array('paypwd'=>md5($data['paypwd'])));
			}
			session('user.paypwd',$data['paypwd']);
			$this->ajaxReturn(array('status'=>1,'msg'=>'修改成功','url'=>U('Member/setting')));
		}
		$this->assign('two','修改支付密码');
		$this->assign('title','setting');
		$this->display();
	}
	//账户首页
	public function account()
	{
		$this->display();
	}

	//投资管理
	public function project_list()
	{
		if (I('project_status')) {
			$project_status = I('project_status');
		}else{
			$project_status = 1;
		}
			//1众筹中
			if ($project_status == '1') {
				$where['project_status'] = array('in','2,6,9,10');
			}
			//2待售中     
			if ($project_status == '2') {
				$where['project_status'] = array('in','3,4,7');
			}
			//3已完成
			if ($project_status == '3') {
				$where['project_status'] = array('in','5,11');
			}
		
		$where['project_is_del'] = 0;
		$where['cs_investment.user_id'] = $_SESSION['user']['user_id'];
		$join[] = 'left join cs_project on cs_investment.project_id = cs_project.project_id';
		parent::page('investment',5,'list','investment_id desc',$where,$join);
		$this->assign('title','project');
		$this->assign('two','投资管理');
		$this->assign('project_status',$project_status);
		$this->display();
	}

	//资金管理
	public function money_list()
	{
		if (IS_POST) {
			$data = I('post.');
			$data['member_recharge_ctime'] = time();
			$data['user_id'] = $_SESSION['user']['user_id'];
			M('member_recharge')->add($data);
			$this->ajaxReturn(array('status'=>1,'msg'=>'充值完成，等待审核'));
		}
		$member = M('user')->where(array('user_id'=>$_SESSION['user']['user_id']))->find();
		$user = M('member_account')->where(array('user_id'=>$_SESSION['user']['user_id']))->select();
		$this->assign('account',$user);
		$this->assign('member',$member);
		$this->display();
	}


	public function build_bank_card()
	{
		$user = M('member_account')->where(array('user_id'=>$_SESSION['user']['user_id']))->select();
		$this->assign('account',$user);
		$this->display();
	}

}