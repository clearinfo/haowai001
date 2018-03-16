<?php

/* 
 * 微信JS-SDK 接口开发
 * 1.分享接口
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
	else{
		if(isset($_GET['customid']) && !empty($_GET['customid'])){
			$worksid = trim($_GET['customid']);
			$url = 'http://api.zmiti.com/v2/weixin/getWxApAppid/customid/'.$worksid;
			$http = new HttpsClient();
			$json = $http->get($url);
			if(!empty($json)){
				$jsonAry = json_decode($json,true);
				$valAry['AppID'] = $jsonAry['wxappid'];
				$valAry['AppSecret'] = $jsonAry['wxappsecret'];
				AppConfig::set('weixin', $valAry);
			}
			$worksid = 'Customid_'.$worksid;
		}
	}
	
	$jssdk = new WechatJssdk($worksid);
	$paramArr = array(
		'durl' => $_GET['durl'],
	);	
	$sing = $jssdk->getSignature($paramArr,$worksid);	
	echo 'jsonFlickrFeed({"signature":"' . $sing . '"})';
}
