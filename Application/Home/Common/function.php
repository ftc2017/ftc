<?php
/**
 * 记录帐户变动
 * @param   int     $user_id        用户id
 * @param   float   $user_money     可用余额变动
 * @param   int     $pay_points     消费积分变动
 * @param   string  $desc    变动说明
 * @param   float   distribut_money 分佣金额
 * @return  bool
 */
function accountLog($user_id, $user_money = 0,$frozen_money = 0, $desc = '',$distribut_money = 0,$type =0){
    /* 插入帐户变动记录 */
    $account_log = array(
        'user_id'       => $user_id,
        'user_money'    => $user_money,
        'frozen_money'    => $frozen_money,
        'change_time'   => time(),
        'desc'   => $desc,
        'type'   => $type,
    );
    /* 更新用户信息 */
    $sql = "UPDATE __PREFIX__users SET user_money = user_money + $user_money," .
        " frozen_money = frozen_money + $frozen_money WHERE user_id = $user_id";
    $Model = new \Think\Model();
    if( $Model->execute($sql)){
    	M('account_log')->add($account_log);
        return true;
    }else{
        return false;
    }
}
?>