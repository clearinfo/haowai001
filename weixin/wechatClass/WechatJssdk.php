<?php

/**
 * 微信JS-SDK接口
 *	1.分享
 * @author liuhaijun_win
 */
class WechatJssdk {
	private $chr='';
	private $logger;
	private $jsapi_ticket;
	
	public function __construct($worksid='') {
		$this->chr=new HttpsClient();
		$this->logger = new Logger();
		$this->jsapi_ticket = APPROOT.'/cache/C_'.$worksid.'_jsapi_ticket.json';
        if(!file_exists($this->jsapi_ticket)){
            file_put_contents($this->jsapi_ticket, '{"jsapi_ticket":"","expire_time":0}');
        }
	}
	public function __destruct(){
	}
	/**
	 * 生成JS-SDK签名
	 * @param array $paramArr
	 * @return string
	 */
	public function getSignature($paramArr,$worksid=''){
		$jsapiTicket = $this->getJsApiTicket($worksid);
		$nonceStr = "Wm3WZYTPz0wzccnW";
		$timestamp = "1488558145";
		$url = $paramArr['durl'];		
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$filename =  APPROOT.'/cache/sing-'.date('Ymd').'.txt';
		file_put_contents($filename, date('Y-m-d H:i:s')."_".$worksid."\r\n",FILE_APPEND);
		file_put_contents($filename, $string."\r\n",FILE_APPEND);
		$signature = sha1($string);
		file_put_contents($filename, $signature."\r\n",FILE_APPEND);
		return $signature;
	}
	/**
	 * 获取JS-SDK jsapi_ticket
	 * @return type
	 */
	private function getJsApiTicket($worksid=''){
		$data = json_decode(file_get_contents($this->jsapi_ticket));
		if ($data->expire_time < time() || $data->expire_time == 0) {
			$access_token = $this->getAccessToken($worksid);
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$access_token;
			$res = json_decode($this->chr->get($url));
			if(isset($res->ticket)){
				$ticket = $res->ticket;
				if ($ticket) {
					$data->expire_time = time() + 6500;
					$data->jsapi_ticket = $ticket;
					$fp = fopen($this->jsapi_ticket, "w");
					fwrite($fp, json_encode($data));
					fclose($fp);
				}
				else{
					$ticket = '';
				}
			}
			else{
				$ticket = '';
			}
		}
		else{
			$ticket = $data->jsapi_ticket;
		}
		return $ticket;
	}
	/**
	 * 获取调用接口凭证
	 * @return string
	 */
	private function getAccessToken($worksid=''){
		$getAccesstoken = new WechatAccessToken($worksid);
		$access_token = $getAccesstoken->get_accesstoken();
		return $access_token;
	}
}
