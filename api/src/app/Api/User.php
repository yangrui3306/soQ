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
				'username' => array('name' => 'username', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '用户名'),
				// 'password' => array('name' => 'password', 'require' => true, 'min' => 8, 'desc' => '用户密码'),
			),
			'Register' => array(
				'username' => array('name' => 'username', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名'),
				'password' => array('name' => 'password', 'require' => true, 'min' => 8, 'max' => 50, 'desc' => '用户密码'),
				'age'      => array('name' => 'age', 'type' => 'int', 'desc' => '年龄'),
				'sex'      => array('name' => 'sex', 'enum', 'range' => array('female', 'male')),
			),
		);
	}
  /**
   * 登录接口
   * @desc 根据账号和密码进行登录操作
   * @return boolean is_login 是否登录成功
   * @return int user_id 用户ID
   */
  public function login() {
      // $username = $this->username;   // 账号参数
      // $password = $this->password;   // 密码参数
			// 更多其他操作……
			$gd = new GD();
			$img = $gd -> getUserVerificationCodeRandom(4);
			$logo = $gd -> getUserDefaultAvatarByName("Dpso");
			// $domain = new Domain();
			// $sex = $domain -> GetUserSexById($this->username);
			$return = new MyStandard();
			$res = $return -> getReturn(1,"获取成功",$logo);
			return $res;
      // return array('is_login' => true, 'user_id' => 8);
	}
		
	public function test(){
		$domain = new Domain();
		$sex = $domain -> getuserSexByName("1234645656");
		return $sex;
	}
} 
