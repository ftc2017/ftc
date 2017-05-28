<?php
namespace Biz\Model;
use Think\Model;

/**
 * 后台用户表
 */
class AdminModel extends Model{
    /**
     * 用户表
     */
    protected $_validate = array(
        array('admin_name','require','用户名必需!'),
        array('admin_name', 'name_are', '帐号名称已经存在！!', self::EXISTS_VALIDATE, 'callback', self::MODEL_INSERT),
        array('admin_phone','/^(0|86|17951)?((1[3|4|5|7|8][0-9]{1})+\d{8})$/','手机格式不正确！',self::VALUE_VALIDATE),     //手机正则
    );

    public function name_are($admin_name){
        $exist = $this->where(array('admin_name' => trim($admin_name), 'admin_is_del' => 0))->find(); 
        return $exist ? false : true;
    }
}