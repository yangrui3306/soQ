<?php
namespace App\Api;
use App\Domain\User as Domain;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Common\GD;

/**
 * 用户模块接口服务
 */
class User extends Api {
	
	public function getRules(){
		return array(
			'login' => array(
				'Name' => array('name' => 'username', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '用户名'),
				'Password' => array('name' => 'password', 'require' => true, 'min' => 6, 'desc' => '用户密码'),
			),
			'add' => array(
				'Name'          => array('name' => 'username', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名', 'source' => 'post'),
				'Password'      => array('name' => 'password', 'require' => true, 'min' => 8, 'max' => 50, 'desc' => '用户密码', 'source' => 'post'),
				// 'Age'      => array('name' => 'age', 'type' => 'int', 'desc' => '年龄', 'source' => 'post'),
				'Sex'           => array('name' => 'sex', 'enum', 'range' => array('female', 'male'), 'source' => 'post'),
				'Phone'         => array('name' => 'phone', 'require' => true, 'desc' => '用户电话', 'source' => 'post'),
				'Class'         => array('name' => 'class', 'desc' => '用户年级', 'source' => 'post'),
				'School'        => array('name' => 'school', 'desc' => '用户学校', 'source' => 'post'),
				'Address'       => array('name' => 'address', 'desc' => '用户地址', 'source' => 'post'),
				'Intro'         => array('name' => 'intro', 'desc' => '用户简介', 'source' => 'post'),
				'Occupation'    => array('name' => 'occupation', 'desc' => '用户职业', 'type' => 'int', 'max' => 1, 'source' => 'post'),
				'Avatar'        => array('name' => 'avatar', 'desc' => '用户头像', 'source' => 'post'),
			),
			'getUser' => array(
				'Name' => array('name' => 'name', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '用户名'),
			),
			'getUid' => array(),
		);
	}

  /**
   * ipso测试接口
   * @desc 根据账号和密码进行登录操作
   * @return 测试数据
   */
  public function test() {
			$gd = new GD();
			$img = $gd -> getUserVerificationCodeRandom(4);
			$logo = $gd -> getUserDefaultAvatarByName("Dpso");
			$return = new MyStandard();
			$res = $return -> getReturn(1,"获取成功",$logo);
			return $res;
	}


	/**
	 * 用户登录
	 * 
	 * @return array(
	 * 	code 状态码
	 * 	msg  返回信息
	 * 	data 返回数据
	 * )
	 */
	public function login(){
		$username = $this -> Name;
		$pass = $this -> Password;
		$domain = new Domain();
		$res = $domain -> login($username, $pass);

		$returnRule = new MyStandard();
		if($res['code'] == 0){
			return $returnRule -> getReturn(0, $res['msg']);
		}
		return $returnRule -> getReturn(1, $res['msg'], 'token');
	}

	/**
	 * 用户注册接口
	 */
	public function add(){
		$user = array(
			'Name'          => $this -> Name,
			'Password'      => $this -> Password,
			'Sex'           => $this -> Sex,
			'Phone'         => $this -> Phone,
			'Class'         => $this -> Class,
			'School'        => $this -> School,
			'Address'       => $this -> Address,
			'Intro'         => $this -> Intro,
			'Occupation'    => $this -> Occupation,
			'Avatar'        => $this -> Avatar,
		);

		$returnRule = new MyStandard();
		$domain = new Domain();
		$res = $domain -> add($user);
		if($res['code'] == 0){
			return $returnRule -> getReturn(0, $res['msg']);
		}
		return $returnRule -> getReturn(1, $res['msg'], 'token');
	}

	/**
	 * 通过用户名获取用户信息
	 */
	public function getUser(){
		$name = $this -> Name;
		$domain = new Domain();
		$returnRule = new MyStandard();
		$res = $domain -> getUserByName($name);
		if($res['code'] == 0){
			return $returnRule -> getReturn(0, $res['msg']);
		}
		return $returnRule -> getReturn(1, $res['msg'], $res['data']);
	}

	/**
	 * 用户主页推荐
	 */
	public function getRecommend()
	{
		
	}

	/**
	 * 获取当前在线人数
	 */
	public function getOnline(){
		$domain = new Domain();
		$count = $domain -> getOnlineByToken();
		return $returnRule -> getReturn(1, '', $count);
	}

	/**
	 * 获取所有用户ID
	 */
	public function getUid(){
		$domain = new Domain();
		$uid = $domain -> getUid();
		$returnRule = new MyStandard();
		return $returnRule -> getReturn(0, '', $uid);
	}
} 
