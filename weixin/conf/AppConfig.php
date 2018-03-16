<?php
/**
 * 微信配置文件
 */
if(AppEnv::isOnline()){//在线状态	
	//数据库
	define('DB_HOST', '127.0.0.1');//本地MySQL主机IP
	define('DB_USER','root');
	define('DB_PWD','mysql8*');
	//微信公众号配置
	define('TOKEN','Ltthzmiti');
	define('APPID','wxfacf4a639d9e3bcc');
	define('APPSECRET','149cdef95c99ff7cab523d8beca86080');
	define('ENCODINGAESKEY','AxkkmpTJjvbfjRsc2LsdmTLNxqPpQXtxek2888WkyCk');
	//微信支付配置
	define('MCHID',''); //商户id
	define('BUSINESSKEY', '');//API支付密钥
	//字符集
	define('CHARSET', 'utf-8');//API支付密钥
}
else{//测试状态	
	//数据库
	define('DB_HOST', 'localhost');
	define('DB_USER','root');
	define('DB_PWD','mysql8*');
	//微信公众号配置
	define('TOKEN','Ltthzmiti');
	define('APPID','wxfacf4a639d9e3bcc');
	define('APPSECRET','149cdef95c99ff7cab523d8beca86080');
	define('ENCODINGAESKEY','AxkkmpTJjvbfjRsc2LsdmTLNxqPpQXtxek2888WkyCk');
	//微信支付配置
	define('MCHID',''); //商户id
	define('BUSINESSKEY', '');//API支付密钥
	//字符集
	define('CHARSET', 'utf-8');//API支付密钥
}

//MySQL数据库：主机;数据库名
define('DB_DSN', 'mysql:host='.DB_HOST.';dbname=zmiti');

class AppConfig{
	private static $data = array(		
		'db' => array(
			'class' => 'PDODB',
			'dsn' => DB_DSN,
			'user' => DB_USER,
			'password' => DB_PWD,
		),
		'weixin' => array(
			'Token' => TOKEN,//令牌
			'AppID' => APPID,//应用ID
			'AppSecret' => APPSECRET,//应用密钥
			'EncodingAESKey' => ENCODINGAESKEY,//消息加密密钥
			'MchID' => MCHID,//商户id 
			'BusinessKey' => BUSINESSKEY, //API支付密钥
			'Charset' => CHARSET,//字符集
		),		
	);	
	
	public static function get($key){
		return isset(self::$data[$key]) ? self::$data[$key] : false;
	}
	
	public static function set($key,$value){
		self::$data[$key] = $value;
	}
}