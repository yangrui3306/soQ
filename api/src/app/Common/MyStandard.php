<?php 
namespace App\Common;


/* ----------------- 返回结构规则定义 -------------------- */

/**
 * 定义公用的接口返回规范
 * @author ipso
 */
class MyStandard {

	/**
	 * 组织返回信息的格式，将返回信息放入数组
	 * @param state 状态码
	 * @param msg   返回信息,
	 * @param data  返回的数据
	 */
	public function getReturn($state = 1, $msg = '', $data = null){
		return array(
			'code' => $state,
			'msg'   => $msg,
			'data'  => $data,
		);
	}

	/**
	 * 组织返回信息的格式，将返回信息放入数组
	 * @param state 状态码
	 * @param msg   返回信息,
	 * @param data  返回的数据
	 */
	public static function gReturn($state = 1, $data = null, $msg = ''){
		return array(
			'code' => $state,
			'msg'   => $msg,
			'data'  => $data,
		);
	}

	/**
	 * 获取分页信息下标数组
	 * @param curr   当前分页
	 * @param pages  记录数量
	 * @param single 单页显示条数
	 */
	public static function getPage($curr, $pages, $single=10){
		$arr = [];
		if($pages > $single && $curr > 3){
			$result = $pages - $curr;
			if($result > 8){
				$j = 0;
				for($i = $curr - 3; $i < $curr + 7; $i++){
					$arr[$j] = $i + 1;
					$j += 1;
				}
				return $arr;
			}
			else{
				$j = 0;
				$start = $single -($pages -$curr);
				for($i =  $curr - $start + 1; $i <= $pages; $i++){
					$arr[$j] = $i;
					$j += 1;
				}
				return $arr;
			}
		}elseif($pages > $single && $curr <= 3){
			for($i = 0; $i < $single; $i++){
				$arr[$i] = $i + 1;
			}
			return $arr;
		}else{
			for($i = 0; $i < $pages; $i++){
				$arr[$i] = $i + 1;
			}
			return $arr;
		}
	}
}