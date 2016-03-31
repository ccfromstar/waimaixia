<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
return array(
	'basePath'=>dirname(__FILE__). DS .'..',
	'name'=>'外卖侠',
    'language' => 'zh_cn',
	'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.extensions.*',
		'ext.giix-components.*', // giix components
		'application.modules.rights.*',
		'application.components.*',
		'application.modules.rights.components.*',
		'application.extensions.phpexcel.*',
	),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
        'admin',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'aaa',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>false,
		),
        'rights' => array(
            'superuserName' => 'Admin',
            'install' => false,
        )
	),

	// application components
	'components'=>array(
		'cache'=>array( 'class'=>'CFileCache'),
		'user'=>array(
            'class' => 'RWebUser',
            'loginUrl' => array('/site/login'),
			'allowAutoLogin'=>true,
		),
        'authManager' => array(
            'class' => 'RDbAuthManager',
        ),

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'urlSuffix' => '.html',
            'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>-<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>-<action:\w+>-cid-<cid:\d+>'=>'<controller>/<action>',
				'<controller:\w+>-<action:\w+>-sid-<sid:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/type/<type:\w+>/id/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>-<action:\w+>-<cid:\d+>-<bid:\d+>'=>'<controller>/<action>',
				'<controller:\w+>-show-<id:\d+>'=>'<controller>/show',
            ),
        ),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=wmx',
			'emulatePrepare' => true,
			'username' => 'wmx',
			'password' => '',
			'charset' => 'utf8',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

        'alipay' => array(
            'class' => 'ext.alipay.AlipayProxy',
        ),

		'ticket' => array(
            'class' => 'application.components.Ticket',
        ),
	),

	'params'=>array(
		'uploadPath'=>'/upload',//编辑器图片路径
		'img_size'=>1024,   
        'configs' => array(
            'base' => array(
                'title' => '基本设置',
                'options' => array(
                    'seo_title' => '网站标题',
                    'seo_keywords' => '关键词',
                    'seo_desc' => array('title' => '网站简介', 'type' => 'text', 'style' => 'height:50px; width:300px; margin-bottom:10px;'),
                    'work_email' => '邮箱',
                    'ipc_no'    => 'IPC备案号',
                )
            ),
			'contact' => array(
                'title' => '联系方式',
                'options' => array(
                    'contact_name' => '公司名称',
                    'contact_addr' => '地址',
                    'contact_tel' => '电话',
                    'contact_mobile' => '手机',
                    'contact_fax' => '传真',
                    'contact_service' => '客服电话',
                    'contact_postalcode' => '邮编',
                )
            ),
			'expaddr' => array(
                'title' => '特殊地址',
                'options' => array(
                    'spaddr' => array('title' => '地址列表', 'type' => 'text', 'style' => 'height:240px; width:200px;', 'tips'=>'注：每行一个地址'),
                )
            ),
			'trade' => array(
                'title' => '交易设置',
                'options' => array(
                    'df_stock' => array('title'=>'默认库存','type'=>'number','style'=>'width:50px;'),
                    'enableorder' => array('title'=>'开启订餐','type'=>'list','data'=>array('否','是')),
                )
            ),
        ),

        'status' => array(
            '不显示',
            '显示',
        ),
		
		//卡券操作类型
		'ticket_way' => array(
			'关注',
			'注册',
			'购买',
			'分享',
			'管理员发放',
		),

		'wechat1'=>array(
			'token'=>'takeaway',
			'appid'=>'wxe2a20ae8d978330b',
			'appsecret'=>'5160fed55fa7f8cffe2677213b270608',
		),

		'wechat'=>array(
			'token'=>'takeaway',
			'appid'=>'wx666cb8cf40ea32d4',
			'appsecret'=>'a1aefa3fa27d68754f989591bcedb2f3',
		),

		'order_status' => array(
            0 => '已生成',
            1 => '已支付',
            2 => '已取消',
            3 => '已作废',
            4 => '已发货',
        ),

		'pay_status' => array(
            '未支付',
            '已支付',
        ),

		'timeline' => array(
			'a' => '11:00-11:30',
			'b' => '11:30-12:00',
			'o' => '12:00-12:30',
			'm' => '12:30-13:00',
		),

		'sms_config' => array(
			'addr' => 'http://121.199.16.178/webservice/sms.php?method=Submit',
			'username' => 'cf_michaelgaolove',
			'pwd' => 'michaelgaolove',
		),
	),
);
