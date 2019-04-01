<?php
namespace App\Api;
use App\Domain\Manager as Domain;
use PhalApi\Api;
use App\Common\MyStandard;

/**
 * 管理员类接口
 */
class Manager extends Api{
	public function getRules(){
		return array(
			'login' => array(
				'Name' => array('name' => 'Name', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 6, 'desc' => '用户密码'),
			),
			'add' => array(
				'Name'  => array('name' => 'Name', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名'),
				'Pass'  => array('name' => 'Pass', 'require' => true, 'min' => 6, 'desc' => '用户密码'),
				'Phone' => array('name' => 'Phone', 'require' => true, 'min' => 6, 'desc' => '用户电话'),
			),
			'getCount' => array(
				
			),
			'getList' => array(
				'Page'  => array('name' => 'Page',  'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '每页数量'),
			),
			'update' => array(
				'Id'  => array('name' => 'Id', 'require' => true, 'desc' => '更新管理员的id'),
				'Name'  => array('name' => 'Name', 'min' => 4, 'max' => 50, 'desc' => '用户名'),
				'Pass'  => array('name' => 'Pass', 'min' => 6, 'desc' => '用户密码'),
				'Phone' => array('name' => 'Phone', 'min' => 6, 'desc' => '用户电话'),
			),
			'delete' => array(
				'Id'  => array('name' => 'Id', 'require' => true, 'desc' => '当前页'),
			),
		);
	}

	/**
	 * @desc 管理员登录
	 */
	public function login(){
		$domain = new Domain();
		$name = $this -> Name;
		$pass = $this -> Pass;
		$res = $domain -> login($name, $pass);
		if($res['code'] == 1){
			return MyStandard::gReturn(1,$res['data'], $res['msg']);
		}
		return MyStandard::gReturn(0,$res['data'], $res['msg']);
	}

	/**
	 * @desc 管理员注册
	 */
	public function add(){
		$domain = new Domain();
		$data = array(
			'Name'  => $this -> Name,
			'Pass'  => $this -> Pass,
			'Phone' => $this -> Phone,
		);

		$res = $domain -> add($data);
		if($res['code'] == 1){
			return MyStandard::gReturn(1,$res['data'], $res['msg']);
		}
		return MyStandard::gReturn(0,$res['data'], $res['msg']);
	}

	/**
	 * @desc 获取管理员数量
	 */
	public function getCount(){
		$domain = new Domain();
		$count = $domain -> getCount();
		return MyStandard::gReturn(0,$count, '获取成功');
	}

	/**
	 * @desc 获取管理员列表
	 */
	public function getList(){
		$domain = new Domain();
		$page = $this -> Page;
		$num  = $this -> Number;
		$res  = $domain -> getList($page, $num);
		if($res['code'] == 1){
			return MyStandard::gReturn(1,$res['list'], $res['msg']);
		}
		return MyStandard::gReturn(0,$res['list'], $res['msg']);
	}

	/**
	 * @desc 根据Id更新一个管理员
	 */
	public function update(){
		$domain = new Domain();
		$id = $this -> Mid;
		$data = array(
			'Name' => $this -> Name,
			'Pass' => $this -> Pass,
			'Phone' => $this -> Phone,
		);

		$res = $domain -> update($id, $data);
		if($res['code'] == 1){
			return MyStandard::gReturn(1,$res['data'], $res['msg']);
		}
		return MyStandard::gReturn(0,$res['data'], $res['msg']);
	}

	/**
	 * @desc 删除一个或多个管理员
	 */
	public function delete(){
		$domain = new Domain();
		$strId = $this -> Id;
		$res = $domain -> delete($strId);
		return MyStandard::gReturn(0,'', '删除成功');
	}
}
