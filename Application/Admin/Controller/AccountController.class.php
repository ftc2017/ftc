<?php

namespace Admin\Controller;

use Think\Controller;

class AccountController extends Controller {



	public function _initialize(){

		if(ACTION_NAME=='login'){

			$this->url = U('Admin/Index/index');

			$refer = parse_url(I('server.HTTP_REFERER'));

			if($refer['host']==I('server.HTTP_HOST')){

				if(stripos($refer['path'],'Login')===false){

					$this->url = $refer['path'];

				}

			}

			if(session('?username')){

				redirect($this->url);

			}

		}

	}



	public function index(){

		redirect(U('Admin/Account/login'));

	}



    public function login(){

		if(IS_GET){

			$this->assign('url',$this->url);

			$this->display();

		}else{

			layout(false);

			if((empty($_POST['name']))||(empty($_POST['password']))){

				$this->error('请填全用户名及密码');

			}

			// $verify = new \Think\Verify();

			// if(!$verify->check($_POST['verify'])){

			// 	$json['status'] = false;

			// 	$json['info'] = '验证码错误';

			// 	exit(json_encode($json));

			// }

			$ban_time = 60*30;					//禁止登录的时间

			$ban_num = 5;						//最多允许的登录错误次数

			$ip = get_client_ip();

			$username = trim($_POST['name']);

			$password = encrypt($_POST['password']);

			$admin = M('admin')->where(array('admin_name'=>$username,'admin_is_del'=>0,'admin_password'=>$password))->find();

			if($admin){

					$data['admin_last_time'] = time();

					$data['error_num'] = 0;

					$data['admin_last_ip'] = $_SERVER['REMOTE_ADDR'];

					M('admin')->where(array('admin_id'=>$admin['admin_id']))->save($data);

                    foreach ($admin as $k=>$v){

                        $_SESSION[$k]=$v;

                    }
                    $role = M('admin_role')->where(array('role_id'=>$admin['role_id']))->find();
                    if ($role['act_list'] == 'all') {
                    	$_SESSION['act_list'] = 'all';
                    }else{
	                    $role = explode(',',$role['act_list']);
	                    //个人权限id
	                    $_SESSION['act_list'] = $role;
	                    //个人权限连接
	                    $role_url = M('system_menu')->where(array('id'=>array('in',$role)))->select();
	                    $role_u = array_columns($role_url,'right');
	                    $_SESSION['right'] = $role_u;
	                    //个人权限分组
	                    $role_group = M('system_menu')->where(array('id'=>array('in',$role)))->group('group_name')->select();
	                    $role_group = array_columns($role_group,'group_name');
	                    $_SESSION['group'] = $role_group;
	                    //全部权限
	                    $all_role_url = M('system_menu')->select();
	                    $all_role_url_u = array_columns($all_role_url,'right');
	                    $_SESSION['all_right'] = $all_role_url_u;
                    }

					$this->success('登录成功',U('Index/index'));

			} else {

				$this->error('用户名密码错误');

			}

		}

    }



    public function verify(){

		$config = array(

					'imageW' => 140,

					'imageH' => 30,

					'length' => 4,

					'fontSize' => 16,

					'useNoise' => false,

					'codeSet'=> '345679AEFHKMNPRTWXY',

					);

        $Verify = new \Think\Verify($config);

		$Verify->entry();

    }



    public function logout(){

		session(null);

		redirect(U('Admin/Account/login'));

    }

    

    function _empty(){

        header("HTTP/1.0 404 Not Found");

        $this->display('Public:404');

    }

}