<?php

/**
 * 微信响应封装类
 * 
 * 返回给微信服务器的请求
 *
 * @author liuhaijun_win
 */
class WechatResponse {
	/* @var $app Wechat */
	private $app;
	
	function __construct($app) {
		$this->app = $app;
	}
	
	// 回应验证消息
	public static function echoValidateMsg(){
		if(isset($_GET["echostr"])){
			echo $_GET["echostr"];
		}
	}	
}
