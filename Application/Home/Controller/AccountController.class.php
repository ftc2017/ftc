<?php
namespace Home\Controller;
use Think\Controller;
class AccountController extends BaseController {
   public function _initialize(){
      parent::_initialize();
      $this->assign('title_top','Account');
    }

   public function login()
   {
         // if ($_SESSION['user']) {
         //    $this->redirect(U('Member/account'));
         // }
   		if (IS_POST) {
   			$data = I('post.');
   			if (!$data['mobile'] || !$data['password']) {
   				$this->ajaxReturn(array('status'=>0,'msg'=>'用户名密码不能为空'));
   			}
   			$user = M('user')->where(array('users_is_del'=>0,'mobile'=>$data['mobile'],'password'=>md5($data['password'])))->find();
   			if ($user) {
   				session('user',$user);
   				$this->ajaxReturn(array('status'=>1,'msg'=>'登录成功','url'=>U('Index/index')));
   			}else{
   				$this->ajaxReturn(array('status'=>0,'msg'=>'用户名密码错误'));
   			}
   		}
   		$this->display();
   }
   public function regist()
   {
      // if ($_SESSION['user']) {
      //       $this->redirect(U('Member/account'));
      //    }
   	if (IS_POST) {
   		$data = I('post.');
   		if (!$data['user_name']) {
   			$this->ajaxReturn(array('status'=>0,'msg'=>'用户名不能为空'));
   		}
   		if (!$data['mobile']) {
   			$this->ajaxReturn(array('status'=>0,'msg'=>'手机号不能为空'));
   		}
   		if (!$data['password'] || $data['password']!=$data['repassword']) {
   			$this->ajaxReturn(array('status'=>0,'msg'=>'密码为空或两次密码不一致'));
   		}
         if (!$data['checkbox']) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'请勾选协议'));
         }
          $status = M('member_smscode')->where(array('member_smscode_code'=>$data['code'],'member_smscode_phone'=>$data['mobile'],'member_smscode_status'=>0,'member_smscode_type'=>1))->find();
         // echo M('member_smscode')->getlastsql();die;
         if (!$status) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'短信验证码错误'));
         }
         $data['password'] = md5($data['password']);
         $data['reg_time'] = time();
         $sta = M('user')->add($data);
         if ($sta) {
            $this->ajaxReturn(array('status'=>1,'msg'=>'注册成功','url'=>U('Account/login')));
         }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'注册失败请重试'));
         }
      }
   	$this->display();
   }
   public function forgetpass_two()
   {
      if (IS_POST) {
         $data = I('post.');
         if (!$data['password']) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'密码为空'));
         }
         $post = array(
            'password'=>md5($data['password']),
            );
         $sta = M('user')->where(array('mobile'=>$data['mobile']))->save($post);
         if ($sta) {
            $this->ajaxReturn(array('status'=>1,'msg'=>'修改成功','url'=>U('Account/login')));
         }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'修改失败请重试'));
         }
      }
      $mobile = I('mobile');
      $this->assign('mobile',$mobile);
      $this->display();
   }

    public function forgetPass()
   {
      if (IS_POST) {
         $data = I('post.');
         if (!$data['mobile']) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'手机号不能为空'));
         }
         if (!$data['code']) {
            $this->ajaxReturn(array('status'=>0,'msg'=>'密码为空'));
         }
         $status = M('member_smscode')->where(array('member_smscode_code'=>$data['code'],'member_smscode_phone'=>$data['mobile'],'member_smscode_status'=>0,'member_smscode_type'=>2))->find();
         if ($status) {
            $this->ajaxReturn(array('status'=>1,'msg'=>'认证成功','url'=>U('Account/forgetpass_two',array('mobile'=>$data['mobile']))));
         }else{
            $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误请重试'));
         }
      }
      $this->display();
   }

   public function logout()
   {
      session('user',null);
      $this->redirect(U('account/login'));
   }

   public function service_terms()
   {
      $info = M('website')->find();
      $this->assign('info',$info);
      $this->display();
   }   
}