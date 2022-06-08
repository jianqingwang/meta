<?php
require dirname(__FILE__).'/secure.php';
return array(
	'DB_TYPE'              => DB_TYPE,
	'DB_HOST'              => DB_HOST,
	'DB_NAME'              => DB_NAME,
	'DB_USER'              => DB_USER,
	'DB_PWD'               => DB_PWD,
	'DB_PORT'              => DB_PORT,
	'DB_PREFIX'            => 'tw_',
	'ACTION_SUFFIX'        => '',
	'MULTI_MODULE'         => true,
	'MODULE_DENY_LIST'     => array('Common', 'Runtime'),
	'MODULE_ALLOW_LIST'    => array('Home', 'Admin', 'Mobile', 'Support'),
	'DEFAULT_MODULE'       => 'Admin', // WHERECOME
	'URL_CASE_INSENSITIVE' => false,
	'URL_MODEL'            => 1,
	'URL_HTML_SUFFIX'      => '',
	'LANG_SWITCH_ON'       => true, //开启多语言支持开关
	
	'LANG_AUTO_DETECT'     => true, // 自动侦测语言
	'DEFAULT_LANG'         => 'zh-cn', // 默认语言
	'LANG_LIST'     	   => 'zh-cn,zh-tw,en-us',
	'VAR_LANGUAGE'         => 'LANG', //默认语言切换变量
    'PTP_MARKET'           => array('USDT','BTC'),
    // 'NATION'    =>array('en_US'=>'美国','ja_JP'=>'日本','ko_KR'=>'韩国','ru_RU'=>'俄罗斯','zh_CN'=>'中国','zh_HK'=>'中国香港')
    'NATION'     =>array('zh_CN'=>'中国','en_US'=>'美国',),
    'COINTR'     =>array('CNY'=>'人民币'),
    'BBCOIN'     =>BBCOIN,
    'BBAPIKEY'   =>BBAPIKEY,
	
	'TMPL_ACTION_ERROR' => './Public/error.html', //默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS' => './Public/success.html', //默认成功跳转对应的模板文件
	//'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
	'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
	//'MAIL_USERNAME' =>'haicaow@163.com',//发件人的邮箱名
	//'MAIL_PASSWORD' =>'360asdf123',//163邮箱发件人授权密码
	//'MAIL_FROM' =>'haicaow@163.com',//发件人邮箱地址
	//'MAIL_FROMNAME'=>'haicaow',//发件人姓名
	'MAIL_CHARSET' =>'utf-8',//设置邮件编码
	'MAIL_ISHTML' =>TRUE,
	);
?>