<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function _initialize(){
    	layout('Index/layout');
        if(strtolower(CONTROLLER_NAME)!=='Base'){
            //是否登录
            if (empty($_SESSION['user']['user_id']) && strtolower(CONTROLLER_NAME)=='member') {
                if(!IS_AJAX){
                    redirect(U('Account/login'));
                    exit;
                }else{
                    $this->error('请先登录',U('Account/login'),true);
                    exit;
                }
            }else{
                if (!IS_AJAX) {
                    // Layout
                    layout('Index/layout');
                }
            }
        }
    }

    //生成及验证短信图片验证码
    public function getVerify(){
        $config = array(
            'expire' => 120,
            'fontSize' => 16,
            'useCurve' => false,
            'useNoise' => false,
            'imageW' => 140,
            'imageH' => 30,
            'length' => 4,
            'codeSet'=> '345679AEFHKMNPRTWXY',
            // 'codeSet'=> '1234567890qwertyuiopasdfghjklzxcvbnm',
        );
        $Verify = new \Think\Verify($config);
        echo $Verify->entry();
        exit;
    }

    public function checVerify(){
        $member_smscode=D('Common/Member_smscode');
        // $member_smscode->newPhoneVerify() || $this->error($member_smscode->errorMsg(),'',true);
        if (I('post.type')==4) {
            $verify = new \Think\Verify();
            $verify->check(I('post.verify')) || $this->error('验证码错误','',true);
        }
        time()-$_SESSION[$session_name]['sendtime']>60 || $this->error('短信发送频繁','',true);
        $code=array('member_smscode_code'=>sprintf('%06s',rand(1,999999)),'member_smscode_type'=>I('post.type'),'member_smscode_phone'=>$_POST['mobile'],'member_smscode_ctime'=>time());
        switch (I('post.type')) {
            case 1:
                $session_name='smscodeL';
                $send='尊敬的朋友,您此次注册的验证码为'.$code['member_smscode_code'].'，工作人员不会向您索取，请勿透漏。';
                $member_smscode->newPhoneVerify($_POST['mobile']) || $this->error($member_smscode->errorMsg(),'',true);
                break;
            case 2:
                $session_name='smscodeF';
                $send='您好，您正在进行找回登录密码操作，切勿将验证码泄露于他人，3分钟内有效。验证码：'.$code['member_smscode_code'];
                M('user')->where(array('mobile'=>I('post.mobile')))->find() || $this->error('该手机号未注册','',true);
                break;
            case 3:
                $session_name='smscodeOP';
                $send='您好，您正在申请将平台手机号变更为'.I('post.mobile').'，切勿将验证码泄露于他人，3分钟内有效。验证码：'.$code['member_smscode_code'];
                break;
            case 4:
                $session_name='smscodeNP';
                $send='修改手机号';
                $member_smscode->newPhoneVerify($_POST['mobile']) || $this->error($member_smscode->errorMsg(),'',true);
                break;
            case 5:
                $session_name='smscodeFP';
                $send='您好，您正在进行设置支付密码操作，切勿将验证码泄露于他人，3分钟内有效。验证码：'.$code['member_smscode_code'];
                break;
            case 6:
                $session_name='smscodeT';
                $send='您好，您正在申请提现'.I('post.money').'元，切勿将验证码泄露于他人，工作人员也不会向您索取，3分钟内有效。验证码：'.$code['member_smscode_code'];
                break;
            case 7:
                $session_name='smscodeB';
                $send='您好，您正在申请解绑银行卡，切勿将验证码泄露于他人，工作人员也不会向您索取，3分钟内有效。验证码：'.$code['member_smscode_code'];
                break;
        }
        $member_smscode->add($code) || $this->error('服务器正忙...','',true);
        $_SESSION[$session_name]['sendtime']=time();
        // if (I('post.type')==2 || I('post.type')==4) {
        //     $_SESSION[$session_name]['mobile']=I('post.mobile');
        // }else{
        //     $_SESSION[$session_name]['mobile']=$_SESSION['user']['mobile'];
        // }
        sendSMS(I('post.mobile'),$send);
        $this->ajaxReturn(array('status'=>1,'msg'=>'成功'));
    }

    public function checkCode(){
        switch (I('post.type')) {
            case 1:
                $session_name='smscodeL';
                break;
            case 2:
                $session_name='smscodeF';
                break;
            case 3:
                $session_name='smscodeOP';
                break;
            case 4:
                $session_name='smscodeNP';
                break;
            case 5:
                $session_name='smscodeFP';
                break;
            case 6:
                $session_name='smscodeT';
                break;
            case 7:
            $session_name='smscodeB';
                break;
            default:
                $this->error('类型非法','',true);
                break;
        }
        M('member_smscode')->where(array('member_smscode_code'=>I('post.code'),'member_smscode_phone'=>$_SESSION[$session_name]['mobile'],'member_smscode_status'=>0,'member_smscode_type'=>I('post.type')))->find() || $this->error('验证码不正确','',true);
        $_SESSION[$session_name]['code']=I('post.code');
        $this->ajaxReturn(array('status'=>1,'msg'=>'成功'));
    }
    
    /**
     * 两种分页方法
     */
    //1
    protected function page($table,$num,$name,$order="",$where="",$join=""){
    	$User = M($table); // 实例化User对象
    	$count      = $User->join($join)->where($where)->count();// 查询满足要求的总记录数
    	$Page       = new \Think\Page($count,$num);// 实例化分页类 传入总记录数和每页显示的记录数
        // $Page = pagenew($Page);
    	$Page = pageFormatBiz($Page);
    	$show       = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
    	$name = $name?:$table;
    	$list = $User->join($join)->order($order)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign($name,$list);// 赋值数据集
    	$this->assign('page',$show);// 赋值分页输出
        return $list;
    }
    
}