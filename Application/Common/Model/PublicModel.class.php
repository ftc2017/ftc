<?php
namespace Common\Model;
use Think\Model;
class PublicModel extends Model{
    public $errorMsg;
    public $emptyField=array();
    public $matchField=array();
    public function verify(){
        $field=$this->emptyField;
        foreach ($field as $k=>$v) {
            if (empty($_POST[$k])) {
                $this->errorMsg=$v.'必需！';
                return false;
            }
        }
        $field=$this->matchField;
        foreach ($field as $v) {
            if (!preg_match($v[1],$_POST[$v[0]])) {
                $this->errorMsg=$v[2];
                return false;
            }
        }
        $this->errorMsg=null;
        return true;
    }
    public function errorMsg(){
        return $this->errorMsg;
    }
}