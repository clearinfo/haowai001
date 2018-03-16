<?php

/**
 * 设置开发、上线状态
 *
 * @author liuhaijun
 */
class AppEnv{
	//const ENV = 'dev';
	//const ENV = 'test';
	const ENV = 'online';

	public static function isDev(){
		return self::ENV === 'dev';//开发状态
	}

	public static function isTest(){
		return self::ENV === 'test';//测试状态
	}

	public static function isOnline(){
		return self::ENV === 'online';//上线状态
	}
}

