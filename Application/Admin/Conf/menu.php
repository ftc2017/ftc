<?php

return	array(	

	'Raise'=>array('name'=>'众筹管理','child'=>array(

				array('name' => '项目管理','child' => array(

						array('name'=>'发起的项目','act'=>'index','op'=>'Raise'),

						array('name'=>'发起待审核项目','act'=>'pending','op'=>'Raise'),

						array('name'=>'认筹中的项目','act'=>'confess_raise','op'=>'Raise'),

						array('name'=>'满标待审核项目','act'=>'full_scale','op'=>'Raise'),

						array('name'=>'待售的项目','act'=>'sale','op'=>'Raise'),

						array('name'=>'待回款的项目','act'=>'receivable','op'=>'Raise'),

						array('name'=>'完成的项目','act'=>'complete','op'=>'Raise'),

						array('name'=>'流标的项目','act'=>'flow_standard','op'=>'Raise'),

						array('name'=>'溢价回购的项目','act'=>'premium','op'=>'Raise'),

						// array('name'=>'清除缓存','act'=>'cleanCache','op'=>'Raise'),

				)),

				array('name' => '众筹运营','child'=>array(

						array('name'=>'运营规则','act'=>'index','op'=>'Operation')

				))

	)),

		

	'Money'=>array('name'=>'资金统计','child'=>array(

				array('name' => '会员账户','child' => array(

					array('name' => '会员账户', 'act'=>'user_account', 'op'=>'Money'),                  

			)),

			array('name' => '充值提现','child'=>array(

					array('name' => '充值记录', 'act'=>'recharge', 'op'=>'Money'),

					array('name' => '提现记录', 'act'=>'withdrawal', 'op'=>'Money'),

			)),

			array('name' => '资金记录','child' => array(

					array('name' => '资金记录', 'act'=>'money_log', 'op'=>'Money'),

			)),

			array('name' => '运营统计','child' => array(

					array('name' => '运营统计', 'act'=>'website', 'op'=>'Money'),

			)),



	)),

		

	'User'=>array('name'=>'会员管理','child'=>array(

			array('name' => '会员管理','child' => array(

					array('name' => '会员列表', 'act'=>'index', 'op'=>'User'),

			)),

			array('name' => '推荐人管理','child' => array(

					// array('name' => '投资记录', 'act'=>'investment_log', 'op'=>'User'),

					array('name' => '推广管理', 'act'=>'promote', 'op'=>'User'),

			)),

			array('name' => '认证管理','child' => array(

					// array('name' => '手机认证会员', 'act'=>'certification_mobile', 'op'=>'user'),

					array('name' => '会员实名认证', 'act'=>'certification_apply', 'op'=>'User'),

			)),

	)),



	'Borrower'=>array('name'=>'借款人管理','child'=>array(

			array('name' => '借款人管理','child' => array(

					array('name' => '借款人管理', 'act'=>'index', 'op'=>'Borrower'),

			)),

	)),

		

	'Recharge'=>array('name'=>'充值管理','child'=>array(

			array('name' => '充值管理','child' => array(

				// array('name' => '在线充值', 'act'=>'index', 'op'=>'Recharge'),

				array('name' => '线下充值', 'act'=>'offline', 'op'=>'Recharge'),

				array('name' => '充值记录总列表', 'act'=>'recharge_list', 'op'=>'Recharge'),

			)),

			array('name' => '提现管理','child' => array(

				array('name' => '待审核提现', 'act'=>'withdrawal_pending', 'op'=>'Recharge'),

				array('name' => '审核通过，处理中', 'act'=>'withdrawal_processed', 'op'=>'Recharge'),

				array('name' => '已提现', 'act'=>'have_withdrawal', 'op'=>'Recharge'),

				array('name' => '审核未通过', 'act'=>'not_pass', 'op'=>'Recharge'),

				array('name' => '提现申请总列表', 'act'=>'apply_for_withdrawal', 'op'=>'Recharge'),

			)),

	)),

	'Article'=>array('name'=>'文章管理','child'=>array(

			array('name' => '文章管理','child' => array(

				array('name' => '文章列表', 'act'=>'lists', 'op'=>'Article'),

				array('name' => '文章分类', 'act'=>'classify_lists', 'op'=>'Article'),

			)),

	)),

	'System'=>array('name'=>'系统管理','child'=>array(

			array('name' => '系统设置','child' => array(

				array('name' => '网站设置', 'act'=>'website_add', 'op'=>'System'),
				array('name' => '轮播设置', 'act'=>'banner_list', 'op'=>'System'),

			)),

			array('name' => '管理员管理','child' => array(

				array('name' => '管理员管理', 'act'=>'index', 'op'=>'System'),

				array('name' => '管理组权限管理', 'act'=>'role', 'op'=>'System'),

				array('name' => '管理员操作日志', 'act'=>'admin_log', 'op'=>'System'),

			)),

			// array('name' => '数据库管理','child' => array(

			// 	array('name' => '备份管理', 'act'=>'sql_backup', 'op'=>'system'),

			// )),

	)),

	'Operation'=>array('name'=>'运营管理','child'=>array(

			array('name' => '参数管理','child' => array(

				array('name' => '运营规则', 'act'=>'operation_rule', 'op'=>'Operation'),

				// array('name' => '业务参数', 'act'=>'bizparam', 'op'=>'Operation'),

				// array('name' => '合同参数', 'act'=>'contract_parameters', 'op'=>'Operation'),

				array('name' => '线下充值银行', 'act'=>'offline_bank', 'op'=>'Operation'),

			)),

			array('name' => '在线客服','child' => array(

				array('name' => 'qq客服管理', 'act'=>'service_qq', 'op'=>'Operation'),

				// array('name' => '客服电话管理', 'act'=>'service_mobile', 'op'=>'Operation'),

			)),

			array('name' => '友情链接','child' => array(

				array('name' => '友情链接', 'act'=>'link_list', 'op'=>'Operation'),

			)),

			array('name' => '众筹红包','child' => array(

				array('name' => '红包列表', 'act'=>'lists', 'op'=>'Packet'),

				array('name' => '红包发放记录', 'act'=>'record', 'op'=>'Packet'),

			)),

	)),
	'Product'=>array('name'=>'商品管理','child'=>array(
	
			array('name' => '商品管理','child' => array(
	
					array('name' => '商品列表', 'act'=>'productList', 'op'=>'Product'),
	
					
	
			)),
	
	)),

);