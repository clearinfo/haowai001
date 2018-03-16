<?php

/* 
 * 下载微信录音临时素材
 * 1.下载提交过来的:"media_id"的录音临时素材到指定目录
 * 3.将录音文件保存路径写回数据库
 */
header("Content-Type:text/html;charset=utf-8");

ini_set("memory_limit", -1); //限制使用最大内存大小:不限制
set_time_limit(60); //脚本执行时间没限制

define('ROOTPATH', dirname(dirname(__FILE__)));//上级目录
/** Error reporting */
define('D_DEBUG', false);//调试状态

if(D_DEBUG){
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
}
else{
	error_reporting(0);//关闭错误报告
	ini_set('display_errors', false);
	ini_set('display_startup_errors', false);
	date_default_timezone_set('Asia/Shanghai');
}
//加载文件
require_once 'autoLoad.php';

if(isset($_REQUEST['media_id']) && isset($_REQUEST['worksid']) && is_string($_REQUEST['media_id']) && is_string($_REQUEST['worksid'])){
	$worksid = strval($_REQUEST['worksid']);
	$media_id = strval($_REQUEST['media_id']);
	if(!empty($media_id) && !empty($worksid)){
		$url = 'http://api.zmiti.com/v2/weixin/getWxApAppid/worksid/'.$worksid;
		$http = new HttpsClient();
		$json = $http->get($url);
		if(!empty($json)){
			$jsonAry = json_decode($json,true);
			$valAry['AppID'] = $jsonAry['wxappid'];
			$valAry['AppSecret'] = $jsonAry['wxappsecret'];
			AppConfig::set('weixin', $valAry);
		}
		$getAccesstoken = new WechatAccessToken($worksid);
		$access_token = $getAccesstoken->get_accesstoken();
		$getMurl = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
		$json2 = $http->get($getMurl);
		
		$filename = ROOTPATH.'/mp3/'.$media_id.'.amr';
		if(!file_exists($filename)){//amr文件不存在,则下载并转换
			saveFile($filename, $json2);
			if(file_exists($filename)){
				$filename_mp3 = ROOTPATH.'/mp3/'.$media_id.'.mp3';
				amr2mp3($filename,$filename_mp3);
				echo '{"code":0,"msg":"保存转换成功","path":"'.$filename_mp3.'"}';
			}
			else{
				file_put_contents('./log/downloadErr_'.date('Ymd').'.txt', $worksid.'|#|'.$media_id."\r\n",FILE_APPEND);
				echo '{"code":3,"msg":"保存文件失败"}';
			}
		}
		else{//amr文件存在
			$filename_mp3 = ROOTPATH.'/mp3/'.$media_id.'.mp3';
			if(!file_exists($filename_mp3)){//mp3文件不存在,则转换
				amr2mp3($filename,$filename_mp3);
				echo '{"code":0,"msg":"保存转换成功","path":"'.$filename_mp3.'"}';
			}
			else{//mp3文件存在,不转换
				echo '{"code":0,"msg":"保存转换成功","path":"'.$filename_mp3.'"}';
			}
		}
	}
	else{
		echo '{"code":2,"msg":"media_id不可为空,worksid不可为空"}';
	}
}
else{
	echo '{"code":1,"msg":"media_id必传,worksid必传"}';
}
/**
 * 保存文件
 * @param string $filename
 * @param string $filecontent
 */
function saveFile($filename, $filecontent){  
    $local_file = fopen($filename, 'w');  
    if (false !== $local_file){//不恒等于（恒等于=== 就是false只能等于false，而不等于0）  
        if (false !== fwrite($local_file, $filecontent)) {  
            fclose($local_file);  
        }  
    }  
} 
/**
 * amr转mp3
 * @param string $amr
 * @param string $mp3
 * @return boolean true:成功,false:失败
 */
function amr2mp3($amr,$mp3){
	$ret = false;
	if(file_exists($mp3) == true){  
		$ret = true;  
	}else{  
		$command = "/usr/local/bin/ffmpeg -i $amr $mp3";  
		$r = @system($command);
		$ret = true;
	}
	return $ret;
}
