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
}