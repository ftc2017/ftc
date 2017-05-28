<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台公共控制器
 */
class AdminController extends Controller {
	/**
     * 后台控制器初始化
     */
	public function _initialize(){
	    if(!$_SESSION['admin_id']){
	        redirect(U('Account/login'));
	    }
	    // dump($_SESSION);die;
		$role_url =CONTROLLER_NAME.'Controller@'.ACTION_NAME;
		if (in_array($role_url, $_SESSION['all_right'])) {
			if ($_SESSION['act_list'] != 'all') {
				if (!in_array($role_url, $_SESSION['right']) && $role_url != 'IndexController@index') {
					$this->error('没有权限访问');
				}
			}
		}
		layout('Index/layout');
	}
	
	/**
	 * 获取列表 且删除状态的不调出
	 */
	public function getList($dbname , $pre = null, $wheresql=null, $ordersql=null, $field = true, $limit = 10){
		$module = M($dbname);
		if(!$pre){
			$pre = $dbname;
		}
	    if ($pre != "no"){
	        $wheresql[] = $pre."_is_del = 0";
	    }
		$count = $module->where($wheresql)->order($ordersql)->count();
		$page = new \Think\Page($count,$limit);
		$show = $page->show();
		$data = $module->where($wheresql)->order($ordersql)->limit($page->firstRow,$page->listRows)->select();

		return array('page' => $show, 'list' => $data);
	}

	public function getListforjoin($dbname, $pre = null, $joinsql, $wheresql = array(), $ordersql=null,$fieldsql=null,$limit = 10){
	    $module = M($dbname);
	    if(!$pre){
	        $pre = $dbname;
	    }
	    if ($pre != 'no'){
	        $wheresql[] = $pre."_is_del = 0";
	    }
		$count = $module->join($joinsql)->where($wheresql)->order($ordersql)->count();
		$page = new \Think\Page($count,$limit);
		$show = $page->show();
		$data = $module->field($fieldsql)->join($joinsql)->where($wheresql)->order($ordersql)->limit($page->firstRow,$page->listRows)->select();

		return array('page' => $show, 'list' => $data, 'firstnum' => $page->firstRow, 'count' => $count);
	}
	/**
	 * 获取不分页列表
	 */
	public function getListNoPage($dbname, $pre = null, $wheresql = array(), $ordersql = null, $field = true){
		$module = M($dbname);
		if(!$pre){
			$pre = $dbname;
		}
	    if ($pre != 'no'){
	      $wheresql[] = $pre."_is_del = 0";
		}
		$data = $module->field($field)->where($wheresql)->order($ordersql)->select();
		return $data;
	}
	
	public function getListNoPageForJoin($dbname, $pre = null, $joinsql, $wheresql = array(), $ordersql = null, $field = true){
	    $module = M($dbname);
	    if(!$pre){
	        $pre = $dbname;
	    }else{
	        if ($pre != 'no'){
	            $wheresql[] = $pre."_is_del = 0";
	        }
	    }
	    $data = $module->join($joinsql)->where($wheresql)->order($ordersql)->select();
	    return $data;
	}

	public function getSum($dbname, $tablename, $wheresql = null){
		$mTable = M($tablename);
		if($wheresql == null){
			$sum = $mTable->sum($dbname);
		}else{
			$sum = $mTable->where($wheresql)->sum($dbname);
		}
		return $sum;
	}

	/**
	 * 权限分类
	 * 调用方法
	 * 	$ac = $this->cate('authority_action', 'ac_status=1','ac_id','ac_pid','ac_level','ac_name');
		$ca = $this->arrToOne($ac);
		dump($ca);exit;
	 * 
	 * 
	 * @param unknown_type $table	表名
	 * @param unknown_type $where	条件
	 * @param unknown_type $id		主键ID
	 * @param unknown_type $pidname	记录上级ID的字段名
	 * @param unknown_type $level	分类等级字段名称
	 * @param unknown_type $name	名称字段
	 * @return Ambigous <\Think\mixed, boolean, string, NULL, mixed, unknown, multitype:, object>
	 */
	public function cate($table,$where='',$id,$pidname,$level,$name){
		$mTable = M($table);
	
		$val = $mTable->where($where.' and '.$level.'=1')->select();
	
		$arr = array();
		
		foreach ($val as $k=>$v){
			
			$val[$k]['con'] = $this->pid($mTable,$v[$id],$pidname,$name,$level);//二级分类
			
			$eval=M($table)->getField('MAX('.$level.')');
			
			foreach ($val[$k]['con'] as $key=>$value){						
				if($value[$id]){
					$levels = $this->pid($mTable,$value[$id],$pidname,$name,$level);
					if($levels){
						$val[$k]['con'][$key]['sub'] = $levels;
					}
				}								
			}
							
		}
		
		$cates = $this->array_multi2single($val);//三维数组转换二维数组（排列顺序不变）
// 		dump($val);exit;
		
		$this-> val = $cates;
	}

/**
	 * 子集查询（无限极分类子方法）
	 * @param unknown_type $dbname
	 * @param unknown_type $pid
	 * @param unknown_type $pidname
	 * @return unknown
	 */
	public function pid($dbname,$pid=0,$pidname,$name,$level,$i){
		$sublevel = $dbname->where($pidname.'='.$pid.' and menu_status=1')->select();
		foreach ($sublevel as $ks=>$cac){
			
			$sublevel[$ks][$name] = str_repeat('&emsp;',$cac[$level]).'┗'.$cac[$name];
		}
		return $sublevel ;
	}

		/**
	 * 三维转一维方法
	 * @param unknown_type $multi	数组
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	public function array_multi2single($array){
		
		static $result_array=array();
		foreach($array as $k=>$value){
			$con = $value['con'];
			$sub = $value['sub'];
			
			unset($value['con']);
			unset($value['sub']);
			
			$result_array[]=$value;
			if(is_array($con)){
				$this->array_multi2single($con);
			 
			}elseif(is_array($sub)){
				$this->array_multi2single($sub);
			}
				
		}
		
		return $result_array;
	}

	/**
	 * 两种分页方法
	 */	
	//1
	protected function page($table,$num,$name,$order="",$where="",$join=""){
		$User = M($table); // 实例化User对象
		$count      = $User->join($join)->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,$num);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page = pageFormatBiz($Page);
		$show       = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$name = $name?:$table;
		$list = $User->join($join)->order($order)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign($name,$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
	}

	//分页
	protected function pageS($User,$num,$order='',$field='*',$where='',$join=''){
        //$User = M($table); // 实例化User对象
        $count      = $User->join($join)->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$num);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->join($join)->order($order)->field($field)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        return $list;
    }
    //批量操作 传送门
    protected function EditAll($table,$data='',$idname='id',$Tidname=''){
        if(empty($table)){
            return false;
        }
        $ids=(is_array($_REQUEST[$idname])?implode(',',$_REQUEST[$idname]):$_REQUEST[$idname]);
        if (empty($ids)) {
        	return false;
        }
        if(!empty($data)){
            if(!is_array($data)){
                return false;
            }
        }
        $table=M($table);
        $Tidname || $Tidname=$table->getPk();
        $res=$table->where(array($Tidname=>array('in',$ids)))->save($data);
        return $res;
    }
    //批量删除
    protected function DelAll($table,$idname='id',$Tidname=''){
        if (empty($table)) {
            return false;
        }
        $ids=(is_array($_REQUEST[$idname])?implode(',',$_REQUEST[$idname]):$_REQUEST[$idname]);
        if (empty($ids)) {
        	return false;
        }
        $table=M($table);
        $Tidname || $Tidname=$table->getPk();
        $res=$table->where(array($Tidname=>array('in',$ids)))->delete();
        return $res;
    }
    //edit操作
    protected function Edit($table,$idname='id'){
        if (empty($table)) {
            return false;
        }
        $table=M($table);
        if (empty($_REQUEST[$idname])) {
            $res='';
        }else{
            $res=$table->where(array($table->getPk()=>$_REQUEST[$idname]))->find();
        }
        return $res;
    }
    //editOk操作
    protected function EditOk($table,$data='',$idname='id'){
        $table=M($table);
        $table->data(array());
        $table->create();
        if (!empty($data)) {
            if (!is_array($data)) {
                return false;
            }
            foreach ($data['all'] as $k=>$v) {
                $table->$k=$v;
            }
        }
        if (empty($_REQUEST[$idname])) {
        	foreach ($data['add'] as $k=>$v) {
                $table->$k=$v;
            }
            $res=$table->add($tdata);
        }else{
        	foreach ($data['save'] as $k=>$v) {
                $table->$k=$v;
            }
            $res=$table->where(array($table->getPk()=>$_REQUEST[$idname]))->save($tdata);
        }
        return $res;
    }
    //del操作
    protected function Del($table,$is_del='',$idname='id',$Tidname=''){
    	if (empty($_REQUEST[$idname])) {
    		return false;
    	}
        $table=M($table);
        $Tidname || $Tidname=$table->getPk();
        if (empty($is_del)) {
        	$res=$table->where(array($Tidname=>$_REQUEST[$idname]))->delete();
        }else{
        	$res=$table->where(array($Tidname=>$_REQUEST[$idname]))->setField($is_del,1);
        }
        return $res;
    }
    public function _empty() {
      R('Empty/_empty');
	}

	//安全验证提交参数方法
	protected function verify_parm($data=array(),$empty=array(),$match=array()){
		if (!empty($empty)) {
			foreach ($empty as $k=>$v) {
				if (empty($data[$v])) {
					return false;
				}
			}
		}
		if (!empty($match)) {
			foreach ($match as $k=>$v) {
				if (!empty($data[$k])) {
					if (!preg_match($v,$data[$k])) {
						return false;
					}
				}
			}
		}
		return true;
	}
	//无限极分类方法
	public function getEval($list,$idName,$nameName,$pidName,$evalName){
		$list1=$list;
		if (empty($list)) {
			return $list;
		}
		$eval=0;
		foreach ($list as $v) {
			if ($v[$evalName]>$eval) {
				$eval=$v[$evalName];
			}
		}
		for ($j=0; $j<=$eval; $j++) {
			foreach ($list1 as $k => $v) {
				if ($v[$evalName]==$j) {
					$exec=false;
					foreach ($list1 as $v1) {
						if ($v[$pidName]==$v1[$idName] || $v[$pidName]==0){
							$exec=true;
							break;
						}
					}
					if (!$exec) {
						unset($list1[$k]);
						$exec=false;
					}
				}
			}
		}
		$pid=0;
		$count=count($list1);
		for ($i=0; $i<$count; $i++) {
			$exec=false;
			foreach($list1 as $k=>$v){
				if ($v[$pidName]==$pid) {
					$v[$nameName]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$v[$evalName]).'-'.$v[$nameName].'-';
					$clist[]=$v;
					$pid=$v[$idName];
					unset($list1[$k]);
					$exec=true;
					break;
				}
			}
			if ($exec) {
				continue;
			}
			$i--;
			foreach($list as $v){
				if ($v[$idName]==$pid) {
					$pid=$v[$pidName];
					break;
				}
			}
		}
		return $clist;
	}
}