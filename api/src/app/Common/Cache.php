<?php 
namespace App\Common;

use PhalApi\Cache\RedisCache;

class Cache{

	/**
	 * 将数据加入缓存
	 * @param key    缓存key值，索引值
	 * @param value  缓存的数据
	 * @param expire 缓存的过期时间 + 加上随机时间，防止缓存雪崩问题单位s
	 */
	public function set($key, $value, $expire){
		// 过期时间加上随机时间1 ~ 10秒
		$randTime = rand(1000, 10000);
		$expire = $expire * 1000 + $randTime; // 实际过期时间
		$redis = new RedisCache();
		return $redis -> set($key, $value, $expire);
	}

	/**
	 * 获取缓存数据
	 * @param key  缓存key值
	 */
	public function get($key){
		$redis = new RedisCache();
		$data = $redis -> get($key);
		if(!$data){
			return false;
		}
		return $data;
	}
}