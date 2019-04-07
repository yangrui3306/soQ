<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\MyStandard;
use App\Model\Loginlog as Model;

/**
 * 登录日志类接口
 */
class Loginlog extends Api{
	public function getRules(){
		return array(
			'getCount' => array(
				
			),
			'getList' => array(
				'Page'  => array('name' => 'Page',  'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '每页数量'),
			),
			'getLive' => array(
			),
		);
	}

	/**
	 * 获取管理员数量
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		return MyStandard::gReturn(0,$count, '获取成功');
	}

	/**
	 * 获取管理员列表
	 */
	public function getList(){
		$model = new Model();
		$page = $this -> Page;
		$num  = $this -> Number;
		$begin = ($page - 1) * $num;
		$res  = $model -> getList($begin, $num);
		if(!$res){
			return MyStandard::gReturn(1,'', '获取失败');
		}
		return MyStandard::gReturn(0,$res, '获取成功');
	}

	/**
	 * 昨日活跃量
	 */
	public function getLive(){
		$model = new Model();
		$count = $model -> getCount();
		return MyStandard::gReturn(0,$count, '获取成功');
	}
}
