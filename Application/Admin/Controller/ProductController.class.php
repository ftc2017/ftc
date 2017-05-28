<?php

namespace Admin\Controller;
use Think\Controller;

class ProductController extends AdminController {

	public function _initialize(){
		parent::_initialize();
		layout('Index/layout');
	}
	
	public function productList(){
		$where[] = 'product_is_del = 0';
		$product = parent::getList('product','product',$where,'','',10);
		$this->assign('list',$product['list']);
		$this->assign('page',$product['page']);
		$this->display();
	}
	
	public function productDel(){
		M('product')->where(array('product_id'=>array('in',I('id'))))->setField('product_is_del',1)||$this->error('操作失败');
		$this->success('操作成功');
	}
	
	public function productEdit(){
		if(IS_POST){
			$tanle = M('product');
			$tanle->create();
			
			$tanle->product_appearance_pic = implode(",", I('product_appearance_pic'));
			$tanle->product_interior_pic = implode(",", I('product_interior_pic'));
			$tanle->product_process_pic = implode(",", I('product_process_pic'));
			$tanle->product_id_pic = implode(",", I('product_id_pic'));
			$tanle->product_content = str_replace('height:460px', 'height:273px', I('product_content'));
			
			if (I('id')){
				$tanle->where(array('product_id'=>I('id')))->save()||$this->error('操作失败');
				$this->success('操作成功');
			}else{
				$tanle-> product_time = time();
				$tanle->add()||$this->error('操作失败');
				$this->success('操作成功',U('Product/productList'));
			}
			
			
		}else{
			
			if(I('id')){
				$xq = M('product')->where(array('product_id'=>I('id')))->find();
				$xq['product_appearance_pic'] = explode(",", $xq['product_appearance_pic']);
				$xq['product_interior_pic'] = explode(",", $xq['product_interior_pic']);
				$xq['product_process_pic'] = explode(",", $xq['product_process_pic']);
				$xq['product_id_pic'] = explode(",", $xq['product_id_pic']);
				$this->xq = $xq;
			}else{
				$this-> content = '
						<table>
                    <thead>
                    <tr><th colspan="4"><h2>基本参数</h2></th></tr>
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
                    <tr><th colspan="4"><h2>发动机参数</h2></th></tr>
                    </thead>
                    <tbody>
                    <tr><td>发动机：</td><td></td><td>燃料类型：</td><td></td></tr>
                    <tr><td>进气形式：</td><td></td><td>燃油标号：</td><td></td></tr>
                    <tr><td>气缸：</td><td></td><td>供油方式：</td><td></td></tr>
                    <tr><td>最大马力(Ps)：</td><td></td><td>排放标准：</td><td></td></tr>
                    <tr><td>最大扭矩(N*m)：</td><td></td></tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                    <tr><th colspan="4"><h2>底盘及制动</h2></th></tr>
                    </thead>
                    <tbody>
                    <tr><td>助力类型：</td><td></td><td>前轮胎规格：</td><td></td></tr>
                    <tr><td>前悬挂类型：</td><td></td><td>后轮胎规格：</td><td></td></tr>
                    <tr><td>后悬挂类型：</td><td></td><td>驻车制动类型：</td><td></td></tr>
                    <tr><td>前制动类型：</td><td></td><td>驱动方式：</td><td></td></tr>
                    <tr><td>后制动类型：</td><td></td></tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                    <tr><th colspan="4"><h2>外部配置</h2></th></tr>
                    </thead>
                    <tbody>
                    <tr><td>电动天窗：</td><td></td><td>后雨刷：</td><td></td></tr>
                    <tr><td>全景天窗：</td><td></td><td>前后电动车窗：</td><td></td></tr>
                    <tr><td>电动吸合门：</td><td></td><td>后视镜电动调节：</td><td></td></tr>
                    <tr><td>感应后备箱：</td><td></td><td>后视镜加热：</td><td></td></tr>
                    <tr><td>感应雨刷：</td><td></td></tr>
                    </tbody>
                </table>';
			}
			
			$this->assign('models',C('models'));
			$this->type = I('type');
			$this->display();
		}
	}
	
	public function productUpdate(){
		$res = M('product');
		$res->create();
		$res->product_appearance_pic = implode(",", I('product_appearance_pic'));
		$res->product_interior_pic = implode(",", I('product_interior_pic'));
		$res->product_process_pic = implode(",", I('product_process_pic'));
		$res->product_id_pic = implode(",", I('product_id_pic'));
		if (I('id')){
			$r = $res->where(array('product_id'=>I('id')))->save();
		}else {
			$res->product_time = time();
			$r = $res->add();
		}
		if ($r) {
			$this->success(I('id')?'修改成功':'添加成功',U('Product/productList'));
		}else {
			$this->error(I('id')?'修改失败':'添加失败',U('Product/productList'));
		}
	}
	/**
	 * 商品预约
	 */
	public function reserve(){
		$id = I('id');
		$data['product_is_reserve'] = 1;
		$data['product_remark'] = '商品已被预订';
		$data['product_reserve_time'] = time();
		M('product')->where(array('product_id'=>$id))->save($data)||$this->error('操作失败');
		$this->success('操作成功',U('Product/productList'));
		
		
	}

	
}

?>