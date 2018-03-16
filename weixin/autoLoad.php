<?php

/* 
 * 自动加载类
 */
date_default_timezone_set('Asia/Shanghai');

//系统常量,根目录
define('APPROOT', dirname(__FILE__));

require_once(APPROOT.'/AppEnv.php');

spl_autoload_register('autoload');

function autoload($className){
	//缺省引入
	$ClassesPath = array('wechatClass', 'class' , 'lib' ,'conf');
	// 特殊的或经常使用的类
	$PreloadClassNameMap = array(
		
	);
	if(isset($PreloadClassNameMap[$className])){
		require_once($PreloadClassNameMap[$className]);
	}
	else{
		foreach ($ClassesPath as $pathName){
			$filename = APPROOT . "/$pathName/$className.php";
			if(file_exists($filename)){
				require_once($filename);
				return;
			}
		}
	}
}

// 错误提示
if(AppEnv::isOnline()){
	define('DEBUG', true);	//上线状态,不记录调试日志,先暂时记录日志
	//关闭错误提示
//	error_reporting(0);//关闭错误报告
//	ini_set('display_errors', false);
//	ini_set('display_startup_errors', false);
	
	//开启错误提示
	error_reporting(E_ALL);//报告所有的PHP错误
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
}
else{
	define('DEBUG', true);//调试状态,将请求与返回的结果写到本地文件
	//开启错误提示
	error_reporting(E_ALL);//报告所有的PHP错误
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
}
