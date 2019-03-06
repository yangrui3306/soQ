<?php
namespace App\Domain;

use App\Model\User as Model;
use App\Model\School;

class User
{

	/**
	 * 获取用户性别(测试)
	 * @param  name 用户名
	 * @return sex  用户性别
	 */
	public function GetUserSexById($id){
		$model = new Model();
		$sex = $model -> getSex($id);
		return $sex;
	}

	/**
	 * 用户名或者是手机号码登录
	 * @param username 用户名或者用户注册手机
	 */
	public function login($username, $pass){

		$model = new Model();
		// 判断是用户名还是电话登录  
		$isPhone = preg_match('/^0?(13|14|15|17|18|19)[0-9]{9}$/', $username, $str);
		if($isPhone == 1){
			$user = $model -> getByPhone($username);
		}else{
			$user = $model -> getByName($username);
		}
		if(!$user)
			return array(
				'code' => 0,
				'msg'  => '用户名或手机号码不存在!',
				'data' => '',
			);
		if($user['Password'] != $pass)
			return array(
				'code' => 0,
				'msg'  => "用户密码不正确!",
				'data' => '',
			);
		return array(
			'code' => 1,
			'msg'  => "登录数据库验证成功!",
			'data' => $user['Id'],
		);
	}

	/**
	 * 用户注册
	 * @param data 包含用户信息的数组
	 */
	public function add($data){
		$model = new Model();
		// 用户名查重
		$name = $model -> getByName($data['Name']);
		if($res != null){
			return array(
				'code' => 0,
				'msg'  => '用户名已存在!',
				'data' => '',
			);
		}
		// 手机正则匹配
		$phone = preg_match('/^0?(13|14|15|17|18|19)[0-9]{9}$/', $data['Phone']);
		if($phone != 1){
			return array(
				'code' => 0,
				'msg'  => '手机号码格式不正确!',
				'data' => '',
			);
		}
		// 手机号码查重
		$isPhone = $model -> getByPhone($data['Phone']);
		if($isPhone != null){
			return array(
				'code' => 0,
				'msg'  => '手机号码已注册!',
				'data' => '',
			);
		}
		// 通过学校名称获取学校id
		$schoolModel = new School();
		$school = $schoolModel -> getByName($data['School']);
		if($school == null){
			return array(
				'code' => 0,
				'msg'  => '学校不存在!',
				'data' => '',
			);
		}
		$data['SchoolId'] = $school['Id'];
		unset($data['School']);
		// 写入数据库
		$sql = $model -> insertOne($data);
		if(!$sql){
			return array(
				'code' => 0,
				'msg'  => '注册操作异常!',
				'data' => '',
			);
		}
		return array(
			'code' => 1,
			'msg'  => '用户注册成功!',
			'data' => '',
		);
	}

	/**
	 * 通过用户名获取用户信息
	 * @param name 用户名
	 */
	public function getUserByName($name){
		$model = new Model();
		// 检查用户名
		$sql = $model -> getByName($name);
		if(!$sql){
			return array(
				'code' => 0,
				'msg'  => '用户名不存在!',
				'data' => '',
			);
		}
		return array(
			'code' => 1,
			'msg'  => '获取用户信息成功!',
			'data' => $sql,
		);
	}

	/**
	 * 获取所有用户的ID
	 */
	public function getUid(){
		$model = new Model();
		$sql = $model -> getUid();
		return $sql;
	}

	/**
	 * 获取tonken数量
	 */
	public function getOnlineByToken(){
		$tokenModel = new Token();
		$count = count($tokenModel -> getAll());
		return $count;
	}
}