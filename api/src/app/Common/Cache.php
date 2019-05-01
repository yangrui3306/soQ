<?php 
namespace App\Common;

// use PhalApi\Cache\RedisCache;
use PhalApi\Redis\Lite as RedisCache;

/**
 * 封装Redis数据类型string和list
 * 针对本系统封装list时只封装lpush,和rpop,即队列的形式
 * 后续有需要可以重新封装此类增加需要的操作
 * @author ipso < 1975386453@qq.com >  2019-05-1
 */
class Cache extends RedisCache{



	/* --------------------  string操作  -------------------- */

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
		$res = \PhalApi\DI()->redis -> set_time($key, $value, $expire);
		if(!$res){
			return false;
		}
		return $key;
	}

	public function get($key){
		if($this -> checkKey($key) == false){
			return false;
		}
		$data = \PhalApi\DI()->redis -> get_time($key);
		if(!$data){
			return false;
		}
		return $data;
	}


	/* --------------------  list操作  -------------------- */

	public function push($key = '', $value = null){
		if($this -> checkKey($key) == false){
			return false;
		}
		$res = \PhalApi\DI()->redis -> set_lPush($key, $value);
		if(!$res){
			return false;
		}
		return $key;
	}

	public function pop($key){
		if($this -> checkKey($key) == false){
			return false;
		}
		$data = \PhalApi\DI()->redis -> get_rPop($key);
		if(!$data){
			return false;
		}
		return $data;
	}

	// ------------------------   未完成rpush   ---------------------------
	// ------------------------   未完成lpop   ---------------------------

	/**
	 * 删除缓存数据,string和list共用
	 * @param key 缓存key值
	 */
	public function delete($key){
		$redis = new RedisCache();
		if($this -> checkKey($key) == false){
			return false;
		}
		$res = \PhalApi\DI()->redis -> del($key);
		if(!$res){
			return false;
		}
		return true;
	}

	/**
	 * 过滤不合法key,预防缓存穿透
	 * @param key 缓存key值
	 */
	private function checkKey($key = null){
		// 如果key值为id时则不允许key值为0或负数
		if(is_int($key) == true && $key <= 0 && $key == null){
			return false;
		}
		return true;
	}
}