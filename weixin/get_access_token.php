<?php

/* 
 * 获取微信公众号access_token
 * 
 */

require_once 'autoLoad.php';

if(!empty($_GET)){
	$worksid = '';
	if(isset($_GET['worksid']) && !empty($_GET['worksid'])){
		$worksid = trim($_GET['worksid']);
		$url = 'http://api.zmiti.com/v2/weixin/getWxApAppid/worksid/'.$worksid;
		$http = new HttpsClient();
		$json = $http->get($url);
		if(!empty($json)){
			$jsonAry = json_decode($json,true);
			$valAry['AppID'] = $jsonAry['wxappid'];
			$valAry['AppSecret'] = $jsonAry['wxappsecret'];
			AppConfig::set('weixin', $valAry);
		}
	}
	
	$getAccesstoken = new WechatAccessToken($worksid);
	$access_token = $getAccesstoken->get_accesstoken();
	echo json_encode(['getret' => 0,'getMsg' => '','accesstoken' => $access_token]);
}
else{
	echo json_encode(['getret' => 1000,'getMsg' => '','accesstoken' => '']);
}


