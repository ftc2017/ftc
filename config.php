<?php
return array(
	//'配置项'=>'配置值'

	/* 项目设定 */
	'APP_SUB_DOMAIN_DEPLOY' => 1, // 是否开启子域名部署
	'APP_SUB_DOMAIN_RULES'  => array( // 子域名部署规则
								'wechat'=>'Wechat'),
	'ACTION_SUFFIX'         =>  '', // 操作方法后缀
	'MODULE_DENY_LIST'      => array('Common','Runtime'), // 禁止访问的模块列表
	'MODULE_ALLOW_LIST'     => array('Home','Biz','Wechat','Api', 'Store','Common'), //分组
	'DEFAULT_GROUP'         => 'Home', //默认分组

	/* 默认设定 */
	'DEFAULT_M_LAYER'       =>  'Model', // 默认的模型层名称
	'DEFAULT_C_LAYER'       =>  'Controller', // 默认的控制器层名称
	'DEFAULT_V_LAYER'       =>  'View', // 默认的视图层名称
	'DEFAULT_LANG'          =>  'zh-cn', // 默认语言
	'DEFAULT_THEME'         =>  '', // 默认模板主题名称
	'DEFAULT_MODULE'        =>  'Home',  // 默认模块
	'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
	'DEFAULT_ACTION'        =>  'index', // 默认操作名称
	'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
	'DEFAULT_TIMEZONE'      =>  'PRC',  // 默认时区
	'DEFAULT_AJAX_RETURN'   =>  'JSON',  // 默认AJAX 数据返回格式,可选JSON XML ...
	'DEFAULT_JSONP_HANDLER' =>  'jsonpReturn', // 默认JSONP格式返回的处理方法
	'DEFAULT_FILTER'        =>  'htmlspecialchars', // 默认参数过滤方法 用于I函数...

	/* Cookie设置 */
	//'COOKIE_EXPIRE'         => 0, // Coodie有效期
	//'COOKIE_DOMAIN'         => '', // Cookie有效域名
	'COOKIE_PATH'           => '/', // Cookie路径
	//'COOKIE_PREFIX'         => '', // Cookie前缀 避免冲突

	/* 数据库设置 */
	'DB_TYPE'               => 'mysql', // 数据库类型
	'DB_HOST'               => '123.56.242.158', // 服务器地址
	'DB_NAME'               => 'ktv', // 数据库名
	'DB_USER'               => 'ktv', // 用户名
	'DB_PWD'                => 'kalaok', // 密码
	'DB_PORT'               => '3306', // 端口
	'DB_PREFIX'             => 'ktv_', // 数据库表前缀
	'DB_CHARSET'            => 'utf8', // 数据库编码默认采用utf8
	'DB_DEPLOY_TYPE'        => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	'DB_RW_SEPARATE'        => false, // 数据库读写是否分离 主从式有效
	'DB_SQL_LOG'            => false, // SQL执行日志记录

	/* 数据缓存设置 */
	'DATA_CACHE_TIME'       => 0, // 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_COMPRESS'   => false, // 数据缓存是否压缩缓存
	'DATA_CACHE_CHECK'      => false, // 数据缓存是否校验缓存
	'DATA_CACHE_PREFIX'     => '', // 缓存前缀
	'DATA_CACHE_TYPE'       => 'File', // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
	'DATA_CACHE_PATH'       => TEMP_PATH, // 缓存路径设置 (仅对File方式缓存有效)
	'DATA_CACHE_SUBDIR'     => false, // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
	'DATA_PATH_LEVEL'       => 1, // 子目录缓存级别

	/* 错误设置 */
	// 'ERROR_MESSAGE'         => '页面错误！请稍后再试～', //错误显示信息,非调试模式有效
	// 'ERROR_PAGE'            => '', // 错误定向页面
	'SHOW_ERROR_MSG'        => false,    // 显示错误信息
	'TRACE_MAX_RECORD'      => 100,    // 每个级别的错误信息 最大记录数

	/* 日志设置 */
	'LOG_RECORD'            => false,   // 默认不记录日志
	'LOG_TYPE'              => 'File', // 日志记录类型 默认为文件方式
	'LOG_LEVEL'             => 'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
	'LOG_EXCEPTION_RECORD'  => false,    // 是否记录异常信息日志

	/* SESSION设置 */
	'SESSION_AUTO_START'    => true, // 是否自动开启Session
	'SESSION_OPTIONS'       => array(), // session 配置数组 支持type name id path expire domain 等参数
	'SESSION_TYPE'          =>  '', // session hander类型 默认无需设置 除非扩展了session hander驱动
	'SESSION_PREFIX'        =>  '', // session 前缀

	/* 模板引擎设置 */
	'TMPL_CONTENT_TYPE'     => 'text/html', // 默认模板输出类型
	'TMPL_ACTION_ERROR'     => THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   => THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
	'TMPL_EXCEPTION_FILE'   => THINK_PATH.'Tpl/think_exception.tpl',// 异常页面的模板文件
	'TMPL_DETECT_THEME'     => false,       // 自动侦测模板主题
	'TMPL_TEMPLATE_SUFFIX'  => '.html',     // 默认模板文件后缀
	'TMPL_FILE_DEPR'        => '/', //模板文件CONTROLLER_NAME与ACTION_NAME之间的分割符
	'TMPL_ENGINE_TYPE'      => 'Think',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
	'TMPL_CACHFILE_SUFFIX'  => '.php',      // 默认模板缓存后缀
	'TMPL_DENY_FUNC_LIST'   => 'echo,exit',    // 模板引擎禁用函数
	'TMPL_DENY_PHP'         => false, // 默认模板引擎是否禁用PHP原生代码
	'TMPL_L_DELIM'          => '{',            // 模板引擎普通标签开始标记
	'TMPL_R_DELIM'          => '}',            // 模板引擎普通标签结束标记
	'TMPL_VAR_IDENTIFY'     => 'array',     // 模板变量识别。留空自动判断,参数为'obj'则表示对象
	'TMPL_STRIP_SPACE'      => true,       // 是否去除模板文件里面的html空格与换行
	'TMPL_CACHE_ON'         => true,        // 是否开启模板编译缓存,设为false则每次都会重新编译
	'TMPL_CACHE_PREFIX'     => '',         // 模板缓存前缀标识，可以动态改变
	'TMPL_CACHE_TIME'       => 0,         // 模板缓存有效期 0 为永久，(以数字为值，单位:秒)
	'TMPL_LAYOUT_ITEM'      => '{__CONTENT__}', // 布局模板的内容替换标识
	'LAYOUT_ON'             => false, // 是否启用布局
	'LAYOUT_NAME'           => 'layout', // 当前布局名称 默认为layout

	/* URL设置 */
	'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
	// 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
	'URL_HTML_SUFFIX'       =>  '',  // URL伪静态后缀设置
	'URL_ROUTER_ON'         =>  false,   // 是否开启URL路由
	'URL_ROUTE_RULES'       =>  array(), // 默认路由规则 针对模块
	'URL_MAP_RULES'         =>  array(), // URL映射定义规则
	'URL_404_REDIRECT'      => APP_PATH . 'Common/View/Default/Tpl/404.html', // 404页面地址
	'OUTPUT_ENCODE'         =>  false, // 页面压缩输出
	'HTTP_CACHE_CONTROL'    =>  'private', // 网页缓存控制

	/* 全局过滤配置 */
	'DEFAULT_FILTER'        => '', //全局过滤函数


	/* 自定义参数 */
	'PROJECT_NAME'			=> 'ktv', // 项目名称
	'BARCODE_PREFIX'		=> '69901',	// 二维码前缀

	/*上传图片设置*/
	'UPLOAD_PIC_SETTING' => array(
			'mimes'    => '', //允许上传的文件MiMe类型
			'maxSize'  => 8388608, //上传的文件大小限制 (0-不做限制)
			'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
			'autoSub'  => true, //自动子目录保存文件
			'subName'  => date('Ym'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
			'rootPath' => './Public/upload/images/', //保存根路径
			'savePath' => '', //保存路径
			'saveName' => time().mt_rand(100000,999999), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
			'saveExt'  => '', //文件保存后缀，空则使用原后缀
			'replace'  => false, //存在同名是否覆盖
			'hash'     => true, //是否生成hash编码
			'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	),
	'WX_API' =>array(
		'encodingAesKey'=> "qwertyuiopasdfghjklzxcvbnm0123456789QWERTYU",
	    'token' 		=> "kechengtoken",
	    'appId' 		=> "wx88ec26885735e05a",
	    'appsecret' 	=> "d7c67c062ae7f90899da6b7751b79757",
	    'return_url' 	=> "http://kc.showxin.net/Api/Openweixin/receive_ticket",
		),
	'WEEKDAYS' => array(
		1 => '周日',
		2 => '周一',
		3 => '周二',
		4 => '周三',
		5 => '周四',
		6 => '周五',
		7 => '周六',
	),
);