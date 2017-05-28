<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class RaiseController extends AdminController {
	public function _initialize(){
		parent::_initialize();
		layout('Index/layout');
		$this->assign('top_title','raise');
	}
	//众筹管理-发起的项目
	public function index()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['project_status'] = array('in','1,8');
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
		$this->display();
	}
	public function add()
	{
		if (IS_POST) {
			$data = I('post.');
			$data['project_appearance_photo'] = ltrim(implode(',',$data['project_appearance_photo']),",");
			$data['project_interior_photo'] = ltrim(implode(',',$data['project_interior_photo']),",");
			$data['project_process_photo'] = ltrim(implode(',',$data['project_process_photo']),",");
			$data['project_id_photo'] = ltrim(implode(',',$data['project_id_photo']),",");
			$data['project_start_time'] = strtotime($data['project_start_time']);
			$data['project_status'] = 1;
			if ($data['project_id']) {
				$sta = M('project')->where(array('project_id'=>$data['project_id']))->save($data);
				$this->success('成功',U('Raise/index'));
			}else{
				// $data['project_time'] = time();
				$sta = M('project')->add($data);
				$this->success('成功',U('Raise/index'));
			}
		}
		$he='<table>
                    <thead>
                    <tr><th colspan="4"><h2>车辆参数</h2></th></tr>
                    </thead>
                    <tbody>
                    <tr><td>品牌：</td><td></td><td>级别：</td><td></td></tr>
                    <tr><td>型号：</td><td></td><td>变速箱：</td><td></td></tr>
                    <tr><td>颜色：</td><td></td><td>驱动形式：</td><td></td></tr>
                    <tr><td>排量：</td><td></td><td>车身结构：</td><td></td></tr>
                    <tr><td>轴距(mm)：</td><td></td><td>整车质保：</td><td></td></tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                    <tr><th colspan="4"><h2>项目参数</h2></th></tr>
                    </thead>
                    <tbody>
                    <tr><td>初次登记日期：</td><td></td><td>包养手册：</td><td></td></tr>
                    <tr><td>车辆年限：</td><td></td><td>备用钥匙：</td><td></td></tr>
                    <tr><td>过户次数：</td><td></td><td>登记证书：</td><td></td></tr>
                    <tr><td>行驶里程：</td><td></td><td>行驶证书：</td><td></td></tr>
                    <tr><td>燃料：</td><td></td><td>原车漆面积：</td><td></td></tr>
                    </tbody>
                </table>';
		if (I('get.id')) {
			$id = I('get.id');
			$this->assign('id',$id);
		}
		// else{
		// 	$id = 6;
		// }
		$cs_operation = M('operation')->find();
		$zc_collect = explode('|',$cs_operation['zc_collect']);
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		$zc_min = explode('|',$cs_operation['zc_min']);
		$test = M('project')->where(array('project_id'=>$id))->find();
		$test['project_appearance_photo'] = explode(',',$test['project_appearance_photo']);
		$test['project_interior_photo'] = explode(',',$test['project_interior_photo']);
		$test['project_process_photo'] = explode(',',$test['project_process_photo']);
		$test['project_id_photo'] = explode(',',$test['project_id_photo']);
		$name = M('borrower')->where(array('borrower_id'=>$test['initiator']))->find();
		$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
		$this->assign('operation',$cs_operation);
		$this->assign('zc_collect',$zc_collect);
		$this->assign('zc_deadline',$zc_deadline);
		$this->assign('zc_min',$zc_min);
		$this->assign('name',$name);
		$this->assign('info',$test);
		$this->assign('he',$he);
		$this->assign('models',C('models'));
		$this->display();
		
	}
	//待审核项目
	public function pending()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['project_status'] = 1;
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
		$this->display();
	}

	//认筹中项目
	public function confess_raise()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['project_status'] = 2;
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
		$this->display();
	}
	//满标待审项目
	public function full_scale()
	{
		$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['project_status'] = 10;
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
		$this->display();
	}
	//出售中项目
	public function sale()
	  {
	  	$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['project_status'] = 3;
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
		$this->display();
	  }
	  //待回款项目
	  public function receivable()
	  {
	  	$data = I('post.');
		if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		$where['project_status'] = 4;
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
		$this->display();
	  }
	  //完成的项目
	  public function complete()
	  {
	  	$data = I('post.');
	  	$status = array('5','11');
	  	$this->_pub($data,$status);
	  	$this->display();
	  }

	  //流标的项目
	  public function flow_standard()
	  {
	  	$data = I('post.');
	  	$status = 6;
	  	$this->_pub($data,$status);
	  	$this->display();
	  }
	  //溢价回购的项目
	  public function premium()
	  {
	  	$data = I('post.');
	  	$status = 7;
	  	$this->_pub($data,$status);
	  	$this->display();
	  }
	  public function _pub($data,$status)
	  {
	  	if($data['start_time'] && $data['end_time']){
			$where['project_start_time'] = array(array('egt',strtotime($data['start_time'])),array('elt',strtotime($data['end_time'])));
		}
		if ($data['models']) {
			$where['project_models'] = $data['models'];
		}
		if ($data['max_deadline']) {
			$where['project_max_deadline'] = $data['max_deadline'];
		}
		if ($data['keyword']) {
			$map['cs_borrower.borrower_name']  = array('like', '%'.$data[keyword].'%');
			$map['cs_borrower.borrower_username']  = array('like','%'.$data[keyword].'%');
			$map['_logic'] = 'or';
			$where['_complex'] = $map;
		}
		if (is_array($status)) {
			$where['project_status'] = array('in',$status);
		}else{

			$where['project_status'] = $status;
		}
		$join[] ='left join cs_borrower on cs_project.initiator = cs_borrower.borrower_id'; 
		$list =$this->getListforjoin('project','',$join,$where,'project_start_time desc','','');
		$dq = M('operation')->find();
		$cs_operation = M('operation')->find();
		$zc_deadline = explode('|',$cs_operation['zc_deadline']);
		foreach ($list['list'] as $key => $value) {
			$list['list'][$key]['zuida'] = intval($value['project_money'])*intval($dq['zc_ge_max'])/100;
			$name = M('borrower')->where(array('borrower_id'=>$value['initiator']))->find();
			$name = $name['borrower_name'] ? $name['borrower_name'] : $name['borrower_username'];
			$list['list'][$key]['initiator'] = $name;
		}
		$this->assign('title','index');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->assign('models',C('models'));
		$this->assign('zc_deadline',$zc_deadline);
	  }
	//修改项目状态
	public function change_sta(){
		$status = I('status');
		$id = I('id');
		$day = I('day');
		$money = I('money');
		if ($money) {
			$sta = M('project')->where(array('project_id'=>$id))->save(array('project_sale_money'=>$money));
		}
		if ($day) {
			$sta = M('project')->where(array('project_id'=>$id))->save(array('project_delay_days'=>$day,'project_status'=>$status,'project_type'=>1));
		}else{
			$sta = M('project')->where(array('project_id'=>$id))->save(array('project_status'=>$status));
			$project = M('project')->where(array('project_id'=>$id))->find();
			$borrower = M('borrower')->where(array('borrower_id'=>initiator))->find();
			$user_ids = M('investment')->where(array('project_id'=>$id))->getfield('user_id',true);
			if ($user_ids) {
				$mobile = M('users')->where(array('user_id'=>array('in',$user_ids)))->getfield('mobile',true);
				$mobiles = implode(',',$mobile);
			}
			if ($status == 2) {
				M('project')->where(array('project_id'=>$id))->save(array('project_pending_time'=>time()));
			}
			if ($status == 3) {
				M('project')->where(array('project_id'=>$id))->save(array('project_time'=>time()));
				if ($borrower['borrower_phone']) {
					sendSMS($borrower['borrower_phone'],'您好，您申请众筹的项目'.$project['project_name'].'，众筹金额'.$project['project_money'].'元，已于'.date('Y-m-d',time()).'通过复审，请与平台客户经理联系。');
				}
				if ($mobiles) {
					sendSMS($mobiles,'您好，您众筹的项目'.$project['project_name'].'，已于'.date('Y-m-d',time()).'通过复审，请关注平台相关项目信息。');
				}
				// sendSMS('','您好，您众筹的项目'.$project['project_name'].'，众筹金额xxx元，已于'.date('Y-m-d',time()).'通过复审，目前平台总额xxx元，可用余额xxx元，请关注平台相关项目信息。');
			}
			if ($status == 5 || $status == 11) {
				$num = ceil((time()-$project['project_time'])/86400);
				//记录完成天数
				M('project')->where(array('project_id'=>$id))->save(array('project_over_day'=>$num));
				//修改用户体验金为未使用状态
				M('users')->where(array('is_experience_money'=>$id))->save(array('is_experience_money'=>0));
			}
		}
		$this->ajaxReturn(array('status'=>1,'msg'=>'成功'));
	}
	//项目流标，投资退回
	public function refund_money()
	{
		if (IS_AJAX) {
			$id = I('get.id');
			if ($id) {
				$investment = M('investment')->where(array('project_id'=>$id,'investment_is_del'=>0))->select();
				$project = M('project')->where(array('project_id'=>$id))->find();
				if ($investment) {
					foreach ($investment as $key => $value) {
						accountLog($value['user_id'],$value['raise_money'],'',$project['project_name'].'项目流标退回','',3);
						M('investment')->where(array('investment_id'=>$value['investment_id']))->save(array('investment_is_del'=>1));
						$user_id[] = $value['user_id'];
					}
				}else{
					$this->ajaxReturn(array('status'=>0,'msg'=>'暂无投资要退回'));
				}
				if ($user_id) {
					$mobile = M('users')->where(array('user_id'=>array('in',$user_id)))->getfield('mobile',true);
					$mobiles = implode(',',$mobile);
				}
				if ($mobiles) {
					sendSMS($mobiles,'您好，您众筹的项目'.$project['project_name'].'，起始日期'.date('Y-m-d',$project['project_start_time']).'，由于众筹期内未满，项目流标，您众筹的金额已经返还到您的平台账户，请登录平台查看新项目信息，谢谢您的理解与支持。');
				}
				$this->ajaxReturn(array('status'=>1,'msg'=>'成功'));
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>'参数错误'));	
			}
		}
		$this->ajaxReturn(array('status'=>0,'msg'=>'参数错误'));
	}
	//获取人
	public function getborrowerAjax()
	{
		$id = I('id');
		$dq = M('borrower')->where(array('borrower_type'=>$id))->select();
		$this->ajaxReturn($dq);
	}
	//获取每人最大额度
	public function getmaxAjax()
	{
		$money = I('money');
		$dq = M('operation')->find();
		$max_money = $dp['zc_ge_max']*$money/100;
		$this->ajaxReturn($max_money);
	}
	//项目投资列表
	public function investment_list()
	{
		$id = I('id');
		$join[] ='left join cs_project on cs_investment.project_id = cs_project.project_id'; 
		$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
		$where['cs_investment.project_id'] = $id; 
		$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_project.project_name,cs_project.project_money,cs_users.user_name,cs_investment.*','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}

	//项目投资收益列表
	public function earnings_list()
	{
		$id = I('id');
		$join[] ='left join cs_project on cs_investment.project_id = cs_project.project_id'; 
		$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
		$where['cs_investment.project_id'] = $id; 
		$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_project.project_name,cs_project.project_money,cs_users.user_name,cs_investment.*','');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display();
	}
	//计算项目收益分配
	public function earnings_count()
	{
		
		$id = I('id');
		$project =M('project')->where(array('project_id'=>$id))->find();
		$this->assign('info',$project);
		$this->display();
	}

	//显示收益列表
	public function earnings_count_list()
	{
			$data= I('post.');
			$project = M('project')->where(array('project_id'=>$data['project_id']))->find();
			//投资者总收益
			$shouyi = ($project['project_sale_money']-$project['project_money'])*$data['lending']/100;
			$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
			$where['cs_investment.project_id'] = $data['project_id']; 
			$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_users.user_name,cs_investment.*','');
		
			// $investment = M('investment')->where(array('project_id'=>$data['id']))->select();
			foreach ($list['list'] as $key => $value) {
				$money = round($value['raise_money']/$project['project_money']*$shouyi,2);
				$list['list'][$key]['investment_earnings'] = $money;
				// M('investment')->where(array('investment_id'=>$value['investment_id']))->save(array('investment_earnings'=>$money));
			}
			$this->assign('list',$list['list']);
			$this->assign('page',$list['page']);
			$this->assign('project_id',$project['project_id']);
			$this->assign('lending',$data['lending']);
			$this->display();
	}

	//发放收益到各个用户
	public function user_earnings()
	{
		if (IS_AJAX) {
			$data= I('get.');
			$project = M('project')->where(array('project_id'=>$data['id']))->find();
			//投资者总收益
			$shouyi = ($project['project_sale_money']-$project['project_money'])*$data['num']/100;
			$investment = M('investment')->where(array('project_id'=>$data['id']))->select();
			foreach ($investment as $key => $value) {
				$money = round($value['raise_money']/$project['project_money']*$shouyi,2);
				M('investment')->where(array('investment_id'=>$value['investment_id']))->save(array('investment_earnings'=>$money));
				$raise_money = M('investment')->where(array('investment_id'=>$value['investment_id']))->getfield('raise_money');
				$zong_money = $money+$raise_money;
				$mobile = M('users')->where(array('user_id'=>$value['user_id']))->getfield('mobile');
				accountLog($value['user_id'],$zong_money,'-'.$raise_money,$project['project_name'],'',5);
				sendSMS($mobile,'您好，您众筹的项目'.$project['project_name'].'，起始日期'.date('Y-m-d',$project['project_start_time']).'于今日回款，回款本金'.$raise_money.'元，回款收益'.$money.'元，已经回到您的平台账户，请登录平台查看具体信息。');
			}
			M('project')->where(array('project_id'=>$data['id']))->save(array('project_status'=>5));
			//修改用户体验金为未使用状态
			M('users')->where(array('is_experience_money'=>$data['id']))->save(array('is_experience_money'=>0));
			$num = ceil((time()-$project['project_time'])/86400);
			M('project')->where(array('project_id'=>$data['id']))->save(array('project_over_day'=>$num));
			//计算平台收益
			$pingtai=$project['project_sale_money']-$project['project_money']-$shouyi;
			$pingsta = M('platform_earnings')->setInc('normal_earnings',$pingtai);
			$this->ajaxReturn(array('status'=>1,'url'=>U('complete')));
		}
	}

	//计算溢价回购
	public function premium_income()
	{
		$id = I('id');
		$project =M('project')->where(array('project_id'=>$id))->find();
		$this->assign('info',$project);
		$this->display();
	}

	//溢价回购收益列表
	public function premium_count_list()
	{
			$data= I('post.');
			$project = M('project')->where(array('project_id'=>$data['project_id']))->find();
			//投资者总收益
			$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
			$where['cs_investment.project_id'] = $data['project_id']; 
			$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_users.user_name,cs_investment.*','');
			foreach ($list['list'] as $key => $value) {
				$money = $value['raise_money']*$data['lending']/365*$project['project_max_deadline'];
				$money = round($money,2);
				$list['list'][$key]['investment_earnings'] = $money;
			}
			$this->assign('list',$list['list']);
			$this->assign('page',$list['page']);
			$this->assign('project_id',$project['project_id']);
			$this->assign('lending',$data['lending']);
			$this->display();
	}

	//发放溢价收益到各个用户
	public function premium_user_earnings()
	{
		if (IS_AJAX) {
			$data= I('get.');
			$project = M('project')->where(array('project_id'=>$data['id']))->find();
			//投资者总收益
			$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
			$where['cs_investment.project_id'] = $project['project_id']; 
			$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_users.user_name,cs_users.mobile,cs_investment.*','');
			foreach ($list['list'] as $key => $value) {
				$money = $value['raise_money']*$data['num']/365*$project['project_max_deadline'];
				$money = round($money,2);
				M('investment')->where(array('investment_id'=>$value['investment_id']))->save(array('investment_earnings'=>$money,'investment_status'=>1));
				// M('users')->where(array('user_id'=>$value['user_id']))->setInc('user_money',$money);
				$raise_money = M('investment')->where(array('investment_id'=>$value['investment_id']))->getfield('raise_money');
				$zong_money = $money+$raise_money;
				accountLog($value['user_id'],$zong_money,'-'.$raise_money,$project['project_name'],'0',6);
				sendSMS($value['mobile'],'您好，您众筹的项目'.$project['project_name'].'，起始日期'.date('Y-m-d',$project['project_start_time']).'于今日回款，回款本金'.$raise_money.'元，回款收益'.$money.'元，已经回到您的平台账户，请登录平台查看具体信息。');
			}
			M('project')->where(array('project_id'=>$data['id']))->save(array('project_status'=>11));
			//修改用户体验金为未使用状态
			M('users')->where(array('is_experience_money'=>$data['id']))->save(array('is_experience_money'=>0));
			$num = ceil((time()-$project['project_time'])/86400);
			M('project')->where(array('project_id'=>$data['id']))->save(array('project_over_day'=>$num));
			//计算平台溢价支出
			$pingsta = M('platform_earnings')->setInc('premium_repo',$money);
			$this->ajaxReturn(array('status'=>1,'url'=>U('complete')));
		}
	}

	//计算逾期回购
	public function overout_income()
	{
		$id = I('id');
		$project =M('project')->where(array('project_id'=>$id))->find();
		$this->assign('info',$project);
		$this->display();
	}

	//逾期回购收益列表
	public function overout_count_list()
	{
			$data= I('post.');
			$project = M('project')->where(array('project_id'=>$data['project_id']))->find();
			$overout_num = overdue($project['project_id']);
			//投资者总收益
			$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
			$where['cs_investment.project_id'] = $data['project_id']; 
			$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_users.user_name,cs_investment.*','');
			foreach ($list['list'] as $key => $value) {
				$money = $value['raise_money']*$data['lending']/10000*$overout_num;
				$money = round($money,2);
				$list['list'][$key]['investment_earnings'] = $money;
			}
			$this->assign('list',$list['list']);
			$this->assign('page',$list['page']);
			$this->assign('project_id',$project['project_id']);
			$this->assign('lending',$data['lending']);
			$this->display();
	}

	//发放逾期收益到各个用户
	public function overout_user_earnings()
	{
		if (IS_AJAX) {
			$data= I('get.');
			$project = M('project')->where(array('project_id'=>$data['id']))->find();
			$overout_num = overdue($project['project_id']);
			//投资者总收益
			$join[] ='left join cs_users on cs_investment.user_id = cs_users.user_id';
			$where['cs_investment.project_id'] = $project['project_id']; 
			$list =$this->getListforjoin('investment','',$join,$where,'investment_id','cs_users.user_name,cs_users.mobile,cs_investment.*','');
			foreach ($list['list'] as $key => $value) {
				$money = $value['raise_money']*$data['num']/10000*$overout_num;
				$money = round($money,2);
				M('investment')->where(array('investment_id'=>$value['investment_id']))->save(array('investment_earnings'=>$money,'investment_status'=>2));
				// M('users')->where(array('user_id'=>$value['user_id']))->setInc('user_money',$money);
				// accountLog($value['user_id'],$money,'0',$project['project_name'],'0',7);
				$raise_money = M('investment')->where(array('investment_id'=>$value['investment_id']))->getfield('raise_money');
				$zong_money = $money+$raise_money;
				accountLog($value['user_id'],$zong_money,'-'.$raise_money,$project['project_name'],'0',7);
				sendSMS($value['mobile'],'您好，您众筹的项目'.$project['project_name'].'，起始日期'.date('Y-m-d',$project['project_start_time']).'于今日回款，回款本金'.$raise_money.'元，回款收益'.$money.'元，已经回到您的平台账户，请登录平台查看具体信息。');
			}
			M('project')->where(array('project_id'=>$data['id']))->save(array('project_status'=>11));
			//修改用户体验金为未使用状态
			M('users')->where(array('is_experience_money'=>$data['id']))->save(array('is_experience_money'=>0));
			$num = ceil((time()-$project['project_time'])/86400);
			M('project')->where(array('project_id'=>$data['id']))->save(array('project_over_day'=>$num));
			//计算平台溢价逾期支出
			$pingsta = M('platform_earnings')->setInc('overdue_repo',$money);
			$this->ajaxReturn(array('status'=>1,'url'=>U('complete')));
		}
	}
	//回款凭证
	public function money_receivable()
	{
		if (IS_POST) {
			$data = I('post.');
			if (!$data['project_id']) {
				$this->error('上传错误');
			}
			$sta = M('project')->where(array('project_id'=>$data['project_id']))->save(array('project_money_receivable'=>$data['project_money_receivable']));
			$this->success('成功',U('index'));
		}
		$id = I('id');
		$info = M('project')->where(array('project_id'=>$id))->find();
		$this->assign('id',$id);
		$this->assign('info',$info);
		$this->display();
	}
	//打款凭证
	public function money_certificates()
	{
		if (IS_POST) {
			$data = I('post.');
			if (!$data['project_id']) {
				$this->error('上传错误');
			}
			$sta = M('project')->where(array('project_id'=>$data['project_id']))->save(array('project_money_certificates'=>$data['project_money_certificates']));
			$this->success('成功',U('index'));
		}
		$id = I('id');
		$info = M('project')->where(array('project_id'=>$id))->find();
		$this->assign('id',$id);
		$this->assign('info',$info);
		$this->display();
	}
	public function indexDel(){
		M('project')->where(array('project_id'=>array('in',I('id'))))->setField('project_is_del',1)||$this->error('操作失败');
		$this->success('操作成功',U('index'));
	}
	

}