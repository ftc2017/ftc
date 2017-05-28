<?php
namespace Common\Controller;
use Think\Controller;
class CommonController extends Controller{

	public function _initialize(){
		// 不允许直接访问
		if(strtolower(MODULE_NAME.'/'.CONTROLLER_NAME)==='common/common'){
			exit;
		}
	}

	/**
	 * Ajax上传图片
	 */
	public function uploadPic(){
		$w = I('w');
		$h = I('h');
		$setting = C("UPLOAD_PIC_SETTING");
		$picTable = D("Pic");
		$info = $picTable->upload($_FILES,$setting);

		$prefix = (isset($setting['cdnHost']) && !empty($setting['cdnHost']) && isset($setting['cdnPath']) && !empty($setting['cdnPath'])) ? $setting['cdnHost'] : '';
		if(!$info) {// 上传错误提示错误信息
			$r['ret'] = 0;
			$r['msg'] = $picTable->getError();
		}else{// 上传成功 获取上传文件信息
			$r['ret'] = 1;
			$r['pid'] = $info['Filedata']['pic_id'];

			$r['data'] = $prefix.$info['Filedata']['pic_path'];
			if($w || $h){
				$r['thumb'] = $prefix.thumb($info['Filedata']['pic_path'], $w, $h);
			}
		}
		returnJSON($r);
	}

}