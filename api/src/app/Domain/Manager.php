<?php 
namespace App\Domain;

use App\Model\Manager as Model;

class Manager{

	/**
	 * 管理员登录
	 * @param name 管理员账号
	 * @param pass 管理员密码
	 */
	public function login($name, $pass){
		$model = new Model();
		// 验证账号是否存在
		$isName = $model -> getByName($name);
		if(!$isName){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '管理员账号不存在'
			);
		}
		// 验证用户密码是否正确
		if($pass != $isName['Pass']){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '管理员密码不正确'
			);
		}
		// 登录成功
		return array(
			'code' => 0,
			'data' => $isName,
			'msg'  => '管理员登录成功'
		);
	}

	/**
	 * 管理员注册
	 */
	public function add($data){
		$model = new Model();
		// 验证管理员账号是否已经存在
		$isName = $model -> getByName($data['Name']);
		if($isName){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '管理员账号已存在'
			);
		}
		// 验证管理员手机是否已经被注册
		$isPhone = $model -> getByPhone($data['Phone']);
		if($isPhone){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '管理员电话已被注册'
			);
		}
		$sql = $model -> insertOne($data);
		if(!$sql){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '操作异常'
			);
		}

		$newM = $model -> getByName($data['Name']);

		return array(
			'code' => 0,
			'data' => $newM['Id'],
			'msg'  => '注册成功'
		);
	}

	/**
	 * 修改管理员信息
	 * @param id 管理员id
	 */
	public function update($id, $data){
		$model = new Model();
		// 检查id是否存在
		$isId = $model -> getById($id);
		if(!$isId){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => 'Id不存在'
			);
		}
		// 验证管理员账号是否已经存在
		$isName = $model -> getByName($name);
		if($isName){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '管理员账号已存在'
			);
		}
		// 验证管理员手机是否已经被注册
		$isPhone = $model -> getByPhone($data['Phone']);
		if($isPhone){
			return array(
				'code' => 1,
				'data' => '',
				'msg'  => '管理员电话已被注册'
			);
		}

		$sql = $model -> updateOne($id, $data);
		return array(
			'code' => 0,
			'data' => '',
			'msg'  => '管理员信息更新成功'
		);
	}

	/**
	 * 获取管理员数量
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		return $count;
	}

	/**
	 * 分页获取管理员列表
	 */
	public function getList($page, $num){
		$model = new Model();
		$begin = ($page - 1) * $num;
		$list = $model -> getList($begin, $num);
		if(!$list){
			return array(
			'code' => 1,
			'data' => '',
			'msg'  => '获取失败'
			);
		}
		return array(
		'code' => 0,
		'list' => $list,
		'msg'  => '获取成功'
		);
	}

	public function delete($strId){
		$model = new Model();
		$Ids = explode(',', $strId);
		$count = count($Ids);
		for($i = 0; $i < $count; $i++){
			$res = $model -> deleteOne($Ids[$i]);
		}
	  return 0;
	}
}