<?php
 use think\Db;
/**
	 * 更改状态
	 */
	function setStatus($dbname, $pre, $ids, $status){
		if($pre == NULL){
			$pre = $dbname;
		}
		$mTable = M($dbname);
		if(!is_array($ids)){
			$arr[] = $ids;
		}else{
			$arr = $ids;
		}

		foreach($arr as $v){
			$mTable->where($pre."_id = '{$v}'")->setField($pre."_status",$status);
		}
		return ;
	}
//根据ac_pid获取父类名称方法
function getmenu_NAME($data){
	if ($data==0){
		return '顶级分类';
	}else{
		$res = M('menu')->where("menu_id=".$data)->find();
		
			return $res['menu_name'];
		
		
	}
	//勿删
	function returnDate($time,$date='Y-m-d'){
		return date($data,$time);
	}
	
	
}
	
	function adminLog($log_info){
    $add['log_time'] = time();
    $add['admin_id'] = session('admin_id');
    $add['log_info'] = $log_info;
    $add['log_ip'] = getIP();
    // $add['log_url'] = request()->baseUrl();
    M('admin_log')->add($add);
}

function getreferees($id){
		$name = M('users')->where(array('user_id'=>$id))->find();
		if ($name) {
			$username = $name['user_name'];
		}
		return $username;
	}

/**
 * 记录帐户变动
 * @param   int     $user_id        用户id
 * @param   float   $user_money     可用余额变动
 * @param   int     $pay_points     消费积分变动
 * @param   string  $desc    变动说明
 * @param   float   distribut_money 分佣金额
 * @return  bool
 */
function accountLog($user_id, $user_money = 0,$frozen_money = 0, $desc = '',$distribut_money = 0,$type =0){
    /* 插入帐户变动记录 */
    $account_log = array(
        'user_id'       => $user_id,
        'user_money'    => $user_money,
        'frozen_money'    => $frozen_money,
        'change_time'   => time(),
        'desc'   => $desc,
        'type'   => $type,
    );
    /* 更新用户信息 */
    $sql = "UPDATE __PREFIX__users SET user_money = user_money + $user_money," .
        " frozen_money = frozen_money + $frozen_money WHERE user_id = $user_id";
    $Model = new \Think\Model();
    if( $Model->execute($sql)){
    	M('account_log')->add($account_log);
        return true;
    }else{
        return false;
    }
}

function getbankname($id)
{
	$bank =C('bank_array');
	$name ='';
	foreach ($bank as $key => $value) {
		if ($value['id'] = $id) {

			$name = $value['name'];
		}

	}
	return $name;
}

function getusername($id)
{
	$user = M('users')->where(array('user_id'=>$id))->find();
	return $user['user_name'];
}

function getadminname($id)
{
	$user = M('admin')->where(array('admin_id'=>$id))->find();
	return $user['admin_name'];
}
function getmoney($id)
{
	$user = M('account_log')->field('SUM(user_money) AS user_money,SUM(frozen_money) AS frozen_money,SUM(recycled_money) AS recycled_money')->where(array('user_id'=>$id))->find();
	return $user['user_money']+$user['frozen_money']+$user['recycled_money'];
}
//推荐人数
function getpeoplenum($id)
{
	$user = M('users')->where(array('referees'=>$id,'users_is_del'=>0))->count();
	return $user;
}
//投资次数
function gettounum($id)
{
	$t_id = M('users')->where(array('referees'=>$id,'users_is_del'=>0))->getField('user_id',true);
	if ($t_id) {
		$user = M('account_log')->where(array('user_id'=>array('in',$t_id),'type'=>3))->count();
	}
	return $user ? $user : 0;
}
//总投资金额
function getallmoney($id)
{	
	$t_id = M('users')->where(array('referees'=>$id,'users_is_del'=>0))->getField('user_id',true);
	if ($t_id) {
		$user =M('account_log')->where(array('user_id'=>array('in',$t_id),'type'=>3))->getField('SUM(frozen_money)');
	}
	return $user ? $user : 0;
}

//计算是否逾期
function overdue($project_id)
{
	$project = M('project')->where(array('project_id'=>$project_id))->find();
	$overdue_time = (time()-($project['project_time']+$project['project_max_deadline']*86400))/86400;
	$overdue_time = ceil($overdue_time);
	return $overdue_time;
}
function lable($data){
	if($data==1){
		return '爆款';
	}else{
		return '--';
	}
}
//项目众筹金额
function raise_money($id)
{
	$raise_money = M('investment')->where(array('project_id'=>$id))->getField('sum(raise_money)');
	if ($raise_money) {
		return $raise_money;
	}else{
		return 0;
	}
}
?>