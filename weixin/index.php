<?php

/* 
 * 微信公共号入口
 * 智媒体公众号
 * 
 */
require_once 'autoLoad.php';

try{
	$config = AppConfig::get('weixin');
	$wechatObj = Wechat::getInstance($config);
	$wechatObj->main();
}
 catch (Exception $e){
	var_dump($e->getMessage());
 }
