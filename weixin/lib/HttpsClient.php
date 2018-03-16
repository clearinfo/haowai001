<?php
/**
 * Curl封装类
 * 使用curl请求微信接口
 * @author liuhaijun_win
 */
class HttpsClient{
	private $ch;
	private $headers=array();//一个用来设置HTTP头字段的数组。使用如下的形式的数组进行设置： array('Content-type: text/plain', 'Content-length: 100') 
	private $opts=array();
	
	/**
	 * 类构造函数
	 */
	public function __construct()
	{
		$this->headers['Pragma']='no-cache';
		$this->headers['Cache-Control'] = 'no-cache'; 
		$this->headers['Accept'] = '*/*'; 
		$this->headers['Accept-Language'] = 'zh-cn'; 
		$this->headers['Connection'] = 'close';
		$this->headers['User-Agent'] = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
		// default opts
		$this->opts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
		$this->opts[CURLOPT_NOPROGRESS] = 1;
		$this->opts[CURLOPT_FOLLOWLOCATION] = 1;
		$this->opts[CURLOPT_MAXREDIRS] = 15;
		$this->opts[CURLOPT_RETURNTRANSFER] = 1;
		$this->opts[CURLOPT_CONNECTTIMEOUT] = 3;
		$this->opts[CURLOPT_TIMEOUT] = 100;//设置cURL允许执行的最长秒数
		$this->opts[CURLOPT_VERBOSE] = 0;
		// init
		$this->ch = curl_init();
	}
	public function setTimeout($connTimeout,$dataTimeout)
	{
		$this->opts[CURLOPT_CONNECTTIMEOUT] = $connTimeout;
		$this->opts[CURLOPT_TIMEOUT] = $dataTimeout;
	}
	
	public function setVerbose($val = 1){
		$this->opts[CURLOPT_VERBOSE] = $val;
	}
	
	public function setUserAgent($ua){
		$this->headers['User-Agent'] = $ua;
	}
	
	public function setReferer($referer){
		$this->opts[CURLOPT_REFERER] = $referer;
	}
	
	public function setCookieFile($filename){
		$this->opts[CURLOPT_COOKIEJAR] = $filename;
		$this->opts[CURLOPT_COOKIEFILE] = $filename;
	}
	
	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
	}
	
	public function setOpt($key, $value)
	{
		$this->opts[$key] = $value;
	}
	/**
	 * 类析构函数
	 */
	public function __destruct()
	{
	if($this->ch)
		{
			curl_close($this->ch);
		}
	}	
	/**
	 * post请求接口
	 * @param string $url
	 * @param mixed $date_json
	 */
	public function post($url,$date_json)
	{
		if(strpos($url, 'https://') === 0){
			return $this->httpsPost($url, $date_json);
		}		
		if(is_string($date_json))
		{
			$postData = $date_json;
		}
		else
		{
			$postDataAry = array();
			foreach($date_json as $key => $value)
			{
				$postDataAry[] = urlencode($key) . "=" . urlencode($value);
			}
			$postData = implode('&', $postDataAry);
		}
		$this->opts[CURLOPT_POST] = 1;
		$this->opts[CURLOPT_HTTPGET] = 0;
		$this->opts[CURLOPT_POSTFIELDS] = $postData;
		return $this->doRequest($url);
	}
	/**
	 * 直接post一个数组
	 * @param string $url
	 * @param array $postData
	 */
	public function postArr($url,$postData)
	{
		if(strpos($url, 'https://') === 0){
			return $this->httpsPost($url, $postData);
		}
		$this->opts[CURLOPT_POST] = 1;
		$this->opts[CURLOPT_HTTPGET] = 0;
		$this->opts[CURLOPT_POSTFIELDS] = $postData;
		return $this->doRequest($url);
	}
	/**
	 * https请求
	 * @param unknown_type $url
	 * @param unknown_type $dataAry
	 */
	private function httpsPost($url, $date_json){
	    curl_setopt($this->ch, CURLOPT_URL, $url);
		// header
		$headerAry = array();
		foreach($this->headers as $key => $value){
			if(empty($value)) continue;
			$headerAry[] = "$key: $value";
		}
		$this->opts[CURLOPT_HTTPHEADER] = $headerAry;
		// exec
		foreach($this->opts as $optKey => $optValue){
			curl_setopt($this->ch, $optKey, $optValue);
		}
		//curl如果想发起的https请求正常:设定为不验证证书和host
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, 1);  //当根据Location:重定向时，自动设置header中的Referer:信息  	
		curl_setopt($this->ch, CURLOPT_POST, 1);//启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $date_json);
		return curl_exec($this->ch);
	}
	/**
	 * get请求接口
	 * @param unknown_type $url
	 * @param unknown_type $retry
	 */
	public function get($url,$retry=1)
	{
		$result = false;
		while($retry--){
			$result = $this->getOnce($url);
			if($this->getCode() == 200)
				break;
			sleep(1);
		}
		return $result;
	}	
	private function getOnce($url)
	{
		$this->opts[CURLOPT_HTTPGET] = 1;//启用时会设置HTTP的method为GET，因为GET是默认是，所以只在被修改的情况下使用
		$this->opts[CURLOPT_POST] = 0;
		if(strpos($url, 'https://') === 0){
			return $this->httpsGet($url);
		}	
		return $this->doRequest($url);
	}
	/**
	 * https的get请求
	 * Enter description here ...
	 * @param unknown_type $url
	 */
	private function httpsGet($url)
	{
		curl_setopt($this->ch, CURLOPT_URL, $url);
		// header
		$headerAry = array();
		foreach($this->headers as $key => $value){
			if(empty($value)) continue;
			$headerAry[] = "$key: $value";
		}
		$this->opts[CURLOPT_HTTPHEADER] = $headerAry;
		// exec
		foreach($this->opts as $optKey => $optValue){
			if(empty($optValue)) continue;
			curl_setopt($this->ch, $optKey, $optValue);
		}
		//curl如果想发起的https请求正常:设定为不验证证书和host
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		return curl_exec($this->ch);
		
	}
	/**
	 * 获取最后一个收到的HTTP代码
	 */
	public function getCode()
	{
		return intval($this->getInfo(CURLINFO_HTTP_CODE));
	}
	/**
	 * 获取一个cURL连接资源句柄的信息
	 * @param int $infoId
	 */
	public function getInfo($infoId)
	{
		return curl_getinfo($this->ch, $infoId);
	}
	/**
	 * 返回一条最近一次cURL操作明确的文本的错误信息
	 */
	public function getError()
	{
		return curl_error($this->ch);
	}
	/**
	 * http请求
	 * @param unknown_type $url
	 */
	private function doRequest($url)
	{
		curl_setopt($this->ch, CURLOPT_URL, $url);
		// header
		$headerAry = array();
		foreach($this->headers as $key => $value){
			if(empty($value)) continue;
			$headerAry[] = "$key: $value";
		}
		$this->opts[CURLOPT_HTTPHEADER] = $headerAry;
		// exec
		foreach($this->opts as $optKey => $optValue){
			if(empty($optValue)) continue;
			curl_setopt($this->ch, $optKey, $optValue);
		}
		return curl_exec($this->ch);
	}	
}
