<?php
namespace Common\Model;
use Think\Model;

class MemberSmscodeModel extends PublicModel {
	public $matchField=array(
		array('type','/^[1-5]{1}$/','类型非法')
	);
	public function newPhoneVerify($phone){
		if (!preg_match('/^(0|86|17951)?((1[3|4|5|7|8][0-9]{1})+\d{8})$/',$phone)) {	//手机正则
			$this->errorMsg='手机号非法';
			return false;
		}
		$res=M('user')->where(array('mobile'=>$phone))->find();
		if (!empty($res)) {
			$this->errorMsg='该手机号注册过';
			return false;
		}
		return true;
	}
}
?>
