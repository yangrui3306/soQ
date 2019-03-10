<?php
namespace App\Api;
use App\Domain\User as Domain;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Common\GD;
use App\Common\Match;

/**
 * 用户
 */
class User extends Api {
	
	public function getRules(){
		return array(
			'login' => array(
				'Name' => array('name' => 'username', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '用户名'),
				'Password' => array('name' => 'password', 'require' => true, 'min' => 6, 'desc' => '用户密码'),
			),
			'add' => array(
				'Name'          => array('name' => 'username', 'require' => true, 'min' => 3, 'max' => 50, 'desc' => '用户名', 'source' => 'post'),
				'Password'      => array('name' => 'password', 'require' => true, 'min' => 6, 'max'=>100,'desc' => '用户密码', 'source' => 'post'),
				// 'Age'      => array('name' => 'age', 'type' => 'int', 'desc' => '年龄', 'source' => 'post'),
				'Sex'           => array('name' => 'sex', 'enum', 'range' => array('female', 'male'), 'source' => 'post'),
				'Phone'         => array('name' => 'phone', 'require' => true, 'desc' => '用户电话', 'source' => 'post'),
				'MClass'         => array('name' => 'class', 'desc' => '用户年级', 'source' => 'post'),
				'SchoolId'        => array('name' => 'schoolId', 'desc' => '用户学校', 'source' => 'post'),
				'Address'       => array('name' => 'address', 'desc' => '用户地址', 'source' => 'post'),
				'Intro'         => array('name' => 'intro', 'desc' => '用户简介', 'source' => 'post'),
				'Occupation'    => array('name' => 'occupation', 'desc' => '用户职业', 'type' => 'int', 'max' => 1, 'source' => 'post'),
			),
			'getUser' => array(
				'Name' => array('name' => 'name', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '用户名'),
			),
			'getUid' => array(),
			'getRecommend'=>array(
				'uid'  => array('name' => 'uid', 'default'=>0, 'desc' => '用户id'),
				'ncnt'  => array('name' => 'notenumber', 'default'=>3, 'desc' => '显示笔记数量'),
				'qcnt'=>array('name'=> 'questionnumber',  'default'=>6,'desc' => '每页显示题目数量'),
			),
			'getCode' => array(),
			'update'=>array(
				'Id' =>array('name' => 'userid', 'require' => true, 'min' => 1,'desc' => '用户Id', 'source' => 'post'),
				// 'Age'      => array('name' => 'age', 'type' => 'int', 'desc' => '年龄', 'source' => 'post'),
				'Sex'           => array('name' => 'sex', 'enum', 'range' => array('female', 'male'), 'source' => 'post'),
				'MClass'         => array('name' => 'class', 'desc' => '用户年级', 'source' => 'post'),
				'SchoolId'        => array('name' => 'schoolId', 'desc' => '用户学校', 'source' => 'post'),
				'Address'       => array('name' => 'address', 'desc' => '用户地址', 'source' => 'post'),
				'Intro'         => array('name' => 'intro', 'desc' => '用户简介', 'source' => 'post'),
				'Occupation'    => array('name' => 'occupation', 'desc' => '用户职业', 'type' => 'int', 'max' => 1, 'source' => 'post'),
			)
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
			$res = $return -> getReturn(1,"获取成功",$img);
			return $res;
	}


	/**
	 * 用户登录
	 * @desc 用户登录
	 * @return array(
	 * 	code 状态码
	 * 	msg  返回信息
	 * 	data 返回数据
	 * )
	 */
	public function login(){
		$username = $this -> Name;
		$pass = $this -> Password;
		$pass= $pass;
		$domain = new Domain();
		$res = $domain -> login($username, $pass);
		
		$returnRule = new MyStandard();
		if($res['code'] == 1){
			return $returnRule -> getReturn(1, $res['msg']);
		}
		return $returnRule -> getReturn(0, '登录成功',$res['data']);
	}

	/**
	 * 用户注册
	 * @desc 用户注册
	 
	 */
	public function add(){
		$user = array(
			'Name'          => $this -> Name,
			'Password'      => $this -> Password,
			'Sex'           => $this -> Sex,
			'Phone'         => $this -> Phone,
			'Class'         => $this -> MClass,
			'SchoolId'        => $this -> SchoolId,
			'Address'       => $this -> Address,
			'Intro'         => $this -> Intro,
			'Occupation'    => $this -> Occupation,
		);

		$returnRule = new MyStandard();
		$domain = new Domain();

		$res = $domain -> add($user);
		if($res == 1){
			return $returnRule -> getReturn(1, $res['msg']);
		}
		return $returnRule -> getReturn(0, 'token',$res['data'] );
	}
	/**
	 * 用户修改
	 * @desc 用户资料更新
	 */
	public function update(){
		$data=array(
			'Id'						=> $this->Id,
			// 'Password'      => $this -> Password,
			'Sex'           => $this -> Sex,
			'Class'         => $this -> MClass,
			'SchoolId'        => $this -> SchoolId,
			'Address'       => $this -> Address,
			'Intro'         => $this -> Intro,
			'Occupation'    => $this -> Occupation,
		);
		
		$domain=new Domain();
		$re=$domain->updateUser($data);
		if($re==0) return MyStandard::gReturn(1,$re);
		return MyStandard::gReturn(0,$re);
	}

	 /**
   * 通过用户名获取用户信息
   * @desc 通过用户名获取用户信息
   */
	
	public function getUser(){
		$name = $this -> Name;
		$domain = new Domain();
		$returnRule = new MyStandard();
		$res = $domain -> getUserByName($name);
		if($res['code'] == 1){
			return $returnRule -> getReturn(1, $res['msg']);
		}
		return $returnRule -> getReturn(0, $res['msg'], $res['data']);
	}
	 /**
   * 用户主页推荐
   * @desc 用户主页推荐,笔记和题目
	 * @return ["Notes"=>[···],"Questions"=>[···]]
   */

	public function getRecommend()
	{
		$uid=$this->uid;
		$ncnt=$this->ncnt;
		$qcnt=$this->qcnt;
	
		$du=new Domain();
		$re=$du->getIndexRecommend($uid,$ncnt,$qcnt);
		return MyStandard::gReturn(0,$re);
	}

	/**
	 * 获取当前在线人数
	 */
	public function getOnline(){
		$domain = new Domain();
		$returnRule = new MyStandard();
		$count = $domain -> getOnlineByToken();
		return $returnRule -> getReturn(1, '', $count);
	}

	/**
	 * 获取所有用户ID
	 * @desc 获取所有用户ID
	 */
	public function getUid(){
		$domain = new Domain();
		$uid = $domain -> getUid();
		$returnRule = new MyStandard();
		return $returnRule -> getReturn(0, '', $uid);
	}


	/**
	 * 获取验证码
	 */
	public function getCode(){
		$gd = new GD();
		$returnRule = new MyStandard();
		$code = $gd -> getUserVerificationCodeRandom(5);
		return $returnRule -> getReturn(0, '', $code);
	}
} 