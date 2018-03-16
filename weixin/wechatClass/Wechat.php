<?php
/**
 * 微信入口封类
 * 功能：主要用于接收微信推送过来的用户数据并自动回复
 * 
 * @author liuhaijun_win
 */

/**
 * 微信配置封装类
 */
class WechatConfig{
	public $token;
	public $appId;
	public $appSecret;
	public $encodingAESKey;
	public $mchID;
	public $businessKey;
	public $charset='utf-8';
	
	
	function __construct($arr=array()) {
		if(isset($arr['Token'])){
			$this->token = $arr['Token'];
		}
		if(isset($arr['AppID'])){
			$this->appId = $arr['AppID'];
		}
		if(isset($arr['AppSecret'])){
			$this->appSecret = $arr['AppSecret'];
		}
		if(isset($arr['EncodingAESKey'])){
			$this->encodingAESKey = $arr['EncodingAESKey'];
		}
		if(isset($arr['MchID'])){
			$this->mchID = $arr['MchID'];
		}
		if(isset($arr['BusinessKey'])){
			$this->businessKey = $arr['BusinessKey'];
		}
		if(isset($arr['charset'])){
			$this->charset = $arr['charset'];
		}
	}
}

class Wechat {

	private $config=null;
	/* @var $request WechatRequest */
	private $request=null;
	/* @var $response WechatResponse */
	private $response=null;
	
	/* @var $menu WechatMenu */	
	private $menu=null;
	/* @var $menu WechatCustom */
	private $custom=null;
	/* @var $menu WechatUpload */
	private $upload=null;
	/* @var $user WechatUser */
	private $user=null;
	/* @var $pay WechatPay */
	private $pay=null;
	
	/* @var $app Wechat */
	private static $app = null;
	
	public static function getInstance($config=null){
		if(self::$app == null){
			if($config == null){
				throw new Exception('config is null');
			}
			self::$app = new Wechat(new WechatConfig($config));
		}
		return self::$app;
	}
	/**
	 * 初始化微信配置
	 * @param type $config
	 * @return WechatConfig WechatConfig对象
	 */
	private function __construct($config){
		$this->config = $config;
	}
	/**
	 * 获取微信配置
	 * @return array
	 */
	public function getConfig(){
		return $this->config;
	}
	/**
	 * 获取微信请求类
	 * @return WechatRequest
	 */
	public function getRequest(){
		if($this->request == null){
			$this->request = new WechatRequest($this);
		}
		return $this->request;
	}
	/**
	 * 获取响应微信请求类
	 * @return WechatResponse
	 */
	public function getResponse(){
		if($this->response == null){
			$this->response = new WechatResponse($this);
		}
		return $this->response;
	}
	
	/**
	 * 微信执行总入口
	 */
	public function main(){
		/* @var $request ZWechatRequest */
		$request = $this->getRequest();
		if($request->checkSignature()){//验证消息的确来自微信服务器
			$this->processRequest();
		}
		else{
			
		}
	}
	/**
	 * 核心:处理微信服务器发送过来的请求
	 * @param type $param
	 */
	private function processRequest() {
		$msg = $this->getRequest()->getMsg();
		if($msg == null){ // 无消息时，简单回应，通过微信接口验证
			$this->getResponse()->echoValidateMsg();
			return;
		}
		
	}
}
