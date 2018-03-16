<?php

/**
 * 记录日志类
 *
 * @author liuhaijun
 */
class Logger {
	
	public static function getInstance(){
		return new Logger();
	}
	public function __construct(){
	}
	/**
	 * 在开发状态下保存日志到文件
	 * @param string $category  日志内容标题
	 * @param string $s 日志内容
	 * @param bool $fileappend true:追加的方式写文件
	 */
	public function debug($category,$s,$fileappend =true){
		if(DEBUG){
			$filename = APPROOT . '/log/wx_' . date('Y.m.d') . '.log';
			if(is_array($s)){
				$s = serialize($s);
			}
			$content = sprintf("%s [$category] \r\n$s\r\n", date('Y-m-d H:i:s'));
			if($fileappend){
				@file_put_contents($filename, $content, FILE_APPEND);
			}
			else{
				@file_put_contents($filename, $content);
			}
		}
	}
	
}
