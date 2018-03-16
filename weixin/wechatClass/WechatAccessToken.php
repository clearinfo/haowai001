<?php

/**
 * 获取ACCESS_TOKEN值,并缓存到文件中
 * access_token是公众号的全局唯一票据，公众号调用各接口时都需使用access_token。
 * 开发者需要进行妥善保存。access_token的存储至少要保留512个字符空间。
 * access_token的有效期目前为2个小时，需定时刷新，重复获取将导致上次获取的access_token失效。
 * 
 * 公众平台的API调用所需的access_token的使用及生成方式说明：
 * 1、为了保密appsecrect，第三方需要一个access_token获取和刷新的中控服务器。而其他业务逻辑服务器所使用的access_token均来自于该中控服务器，不应该各自去刷新，否则会造成access_token覆盖而影响业务
 * 2、目前access_token的有效期通过返回的expire_in来传达，目前是7200秒之内的值。中控服务器需要根据这个有效时间提前去刷新新access_token。在刷新过程中，中控服务器对外输出的依然是老access_token，此时公众平台后台会保证在刷新短时间内，新老access_token都可用，这保证了第三方业务的平滑过渡；
 * 3、access_token的有效时间可能会在未来有调整，所以中控服务器不仅需要内部定时主动刷新，还需要提供被动刷新access_token的接口，这样便于业务服务器在API调用获知access_token已超时的情况下，可以触发access_token的刷新流程。
 * 
 * 如果第三方不使用中控服务器，而是选择各个业务逻辑点各自去刷新access_token，那么就可能会产生冲突，导致服务不稳定。
 * 
 * @author liuhaijun
 */

class WechatAccessToken {
	private $appId;
	private $appSecret;
	private $chr;
    private $access_token_file ;
	private $logger;
	
	public function __construct($worksid='') {
		$this->logger = new Logger();
		$appconfig = AppConfig::get('weixin');
		$this->appId = $appconfig['AppID'];
		$this->appSecret = $appconfig['AppSecret'];
		$this->chr=new HttpsClient();
        $this->access_token_file = APPROOT.'/cache/C_'.$worksid.'_access_token.json';
        if(!file_exists($this->access_token_file)){
            file_put_contents($this->access_token_file, '{"expire_time":0,"access_token":""}');
        }
	}
	/**
	 * 获取ACCESS_TOKEN值
	 * @return string 
	 */
	public function get_accesstoken(){
        $data = json_decode(file_get_contents($this->access_token_file));
        if ($data->expire_time < time()){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $r = $this->chr->get($url);
			$code=$this->chr->getCode();
			$filename =  APPROOT.'/cache/get-accesstoken-'.date('Ymd').'.txt';
			file_put_contents($filename, date('Y-m-d H:i:s')."_".$url."\r\n",FILE_APPEND);
			file_put_contents($filename, date('Y-m-d H:i:s')."_".$r."\r\n",FILE_APPEND);
			if(!empty($r) && $code=='200'){
				$this->logger->debug('获取asscess_token结果:',$url."\r\n结果:".$r);
				$res = json_decode($r);
				if(isset($res->access_token)){
					$access_token = $res->access_token;
					if ($access_token){
						$data->expire_time = time() + 6000;
						$data->access_token = $access_token;
						$fp = fopen($this->access_token_file, "w");
						fwrite($fp, json_encode($data));
						fclose($fp);
					}
				}
				else{
					$this->logger->debug('获取asscess_token结果失败:',$url."\r\n结果:".$r);
					$access_token = '';
				}
			}
			else{
				$this->logger->debug('获取asscess_token结果失败:',$url."\r\n结果:".$r);
				$access_token = '';
			}
        }
        else{
            $access_token = $data->access_token;
        }
        return $access_token;
	}
	/**
	 * 重新直接获取accesstoken
	 * @return string
	 */
	public function rest_get_accesstoken(){
		$data = json_decode(file_get_contents($this->access_token_file));
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
		$r = $this->chr->get($url);
		$code=$this->chr->getCode();
		if(!empty($r) && $code=='200'){
			$this->logger->debug('获取asscess_token结果:',$url."\r\n结果:".$r);
			$res = json_decode($r);
			$access_token = $res->access_token;
			if ($access_token){
				$data->expire_time = time() + 6000;
				$data->access_token = $access_token;
				$fp = fopen($this->access_token_file, "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		}
		else{
			$this->logger->debug('获取asscess_token结果失败:',$url."\r\n结果:".$r);
			$access_token = '';
		}
		return $access_token;
	}
}
