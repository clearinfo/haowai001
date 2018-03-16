<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'autoLoad.php';

if(!empty($_GET)){
	$worksid = '';
	if($_GET['worksid'] && !empty($_GET['worksid'])){
		$worksid = trim($_GET['worksid']);
		$url = 'http://api.zmiti.com/v2/weixin/getWxApAppid/worksid/'.$worksid;
		$http = new HttpsClient();
		$json = $http->get($url);
		//echo $json;
		if(!empty($json)){
			$jsonAry = json_decode($json,true);
			$valAry['AppID'] = $jsonAry['wxappid'];
			$valAry['AppSecret'] = $jsonAry['wxappsecret'];
			AppConfig::set('weixin', $valAry);
		}
		$getAccesstoken = new WechatAccessToken($worksid);
		$access_token = $getAccesstoken->get_accesstoken();
		//echo json_encode(['getret' => 0,'getMsg' => '','accesstoken' => $access_token]);
		
		//$getMurl = 'https://api.weixin.qq.com/cgi-bin/media/get/jssdk?access_token='.$access_token.'&media_id=w-SBiHBTqu1Ku65yXZYawXFklEi1CVFrFrloiWZR4eTI-cLckS8NSPjPwhoJoKf1';
		$getMurl = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id=w-SBiHBTqu1Ku65yXZYawXFklEi1CVFrFrloiWZR4eTI-cLckS8NSPjPwhoJoKf1';
		var_dump($getMurl);
		$http->setOpt(CURLOPT_HEADER, true);
		$json2 = $http->get($getMurl);
		echo $json2;
		saveFile('liuhaijun.mp3', $json2);
	}
	else{
		echo 'error';
	}
}
else{
	echo 'no get';
}


function saveFile($filename, $filecontent){  
    $local_file = fopen($filename, 'w');  
    if (false !== $local_file){//不恒等于（恒等于=== 就是false只能等于false，而不等于0）  
        if (false !== fwrite($local_file, $filecontent)) {  
            fclose($local_file);  
        }  
    }  
}  