<?php
namespace Common\Model;
use Think\Model;

class MemberModel extends PublicModel {
	public $matchField=array(
		array('password','/^(\w){6,20}$/','密码非法'),
	);
	public function ckeckCode($phone,$code,$type){
		$res=M('member_smscode')->where(array('member_smscode_code'=>$code,'member_smscode_phone'=>$phone,'member_smscode_status'=>0,'member_smscode_type'=>$type))->find();
		if (empty($res)) {
			$this->errorMsg='短信验证码不正确或已失效';
			return false;
		}
		return true;
	}
}
?>
