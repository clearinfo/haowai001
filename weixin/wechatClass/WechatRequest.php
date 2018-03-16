<?php

/**
 * 微信请求封装类
 * 
 * 微信服务器发送过来的请求
 *
 * @author liuhaijun_win
 */
class WechatRequest {
	/* @var $app Wechat */
	private $app;
	/* @var $msg WechatMsg */
	private $msg=null;
	
	function __construct($app) {
		$this->app = $app;
	}
	
	/**
	 * 测试标识
	 * @return bool
	 */
	public function isDebug(){
		return isset($_GET['debug']);
	}

	/**
	 * 验证消息的确来自微信服务器:对微信服务器请求进行校验
	 * @return boolean
	 */
	public function checkSignature(){
		$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
		$token = 'Ltthzmiti';
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取消息
	 * @return WechatMsg
	 */
	public function getMsg(){
		if($this->msg == null){
			if($this->isDebug()){ // 测试用
				
			}
			else{
				$this->msg = $this->parseMsg();
			}
		}
		return $this->msg;
	}
	
	/**
	 * 解析消息
	 * @return WechatMsg
	 */
	public function parseMsg(){
		$ret = null;
		$postStr = file_get_contents("php://input");
		if(!empty($postStr)){
			$ret = null;//WechatMsg::parseMsg($postStr, $this->app->getConfig()->charset);
		}
		return $ret;
	}
}
