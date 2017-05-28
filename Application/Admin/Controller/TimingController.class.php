<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class TimingController extends Controller {

	public function _initialize(){
		// if(get_client_ip()!=$_SERVER['SERVER_ADDR']){
		// 	echo 'out';
		// 	exit;
		// }
	}

	// public function task_1minute(){

	// }

	public function task_1day(){
		//计算流标
		$liubiao = M('project')->where(array('project_status'=>2))->select();
		if ($liubiao) {
			foreach ($liubiao as $key => $value) {
				if (($value['project_start_time']+$value['project_time_limit']*86400)<time()) {
					$arr_liu[]=$value['project_id'];
				}
			}
			if ($arr_liu) {
				M('project')->where(array('project_id'=>array('in',$arr_liu)))->save(array('project_status'=>6));
			}
		}
		//计算溢价
		$yijia = M('project')->where(array('project_status'=>3,'project_time'=>array('neq','0')))->select();
		if ($yijia) {
			foreach ($yijia as $key => $value) {
				if (($value['project_time']+$value['project_max_deadline']*86400<time())) {
					$arr_yi[] = $value['project_id'];
				}
			}
			if ($arr_yi) {
				M('project')->where(array('project_id'=>array('in',$arr_yi)))->save(array('project_status'=>7));
			}
		}
		M('admin_log')->add(array('log_info'=>'WOZHIXINGLE'));
		exit;
	}

}