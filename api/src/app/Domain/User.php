<?php
namespace App\Domain;

use App\Model\User as Model;
use App\Model\School;
use App\Model\Question\Search as ModelQSearch;
use App\Model\MistakeCategory as ModelMistakeCategory;
use App\Domain\Question\Recommend;
use App\Model\Note as ModelNote;
use App\Model\Notecategory as ModelNoteCategory;
use PhalApi\Exception;
use App\Domain\Question\Basic as QBasic;
use App\Model\Focus as ModelFocus;

class User
{
	const recommendDate=10;// 推荐算法中行为分析天数
	/**
	 * 获取用户性别(测试)
	 * @param  name 用户名
	 * @return sex  用户性别
	 */
	public function GetUserSexById($id)
	{
		$model = new Model();
		$sex = $model->getSex($id);
		return $sex;
	}

	/**
	 * 用户名或者是手机号码登录
	 * @param username 用户名或者用户注册手机
	 */
	public function login($username, $pass)
	{
		$model = new Model();
		// 判断是用户名还是电话登录  
		$isPhone = preg_match('/^0?(13|14|15|17|18|19)[0-9]{9}$/', $username, $str);
		if ($isPhone == 1) {
			$user = $model->getByPhone($username);
		} else {
			$user = $model->getByName($username);
		}
		if (!$user)
			return array(
				'code' => 1,
				'msg'  => '用户名或手机号码不存在!',
				'data' => '',
			);
		if ($user['Password'] != $pass)
			return array(
				'code' => 1,
				'msg'  => "用户密码不正确!",
				'data' => '',
			);
		return array(
			'code' => 0,
			'msg'  => "登录数据库验证成功!",
			'data' => $user,
		);
	}

	/**
	 * 用户注册
	 * @param data 包含用户信息的数组
	 */
	public function add($data)
	{
		$model = new Model();
		// 用户名查重
		$name = $model->getByName($data['Name']);
		if ($name != null) {
			return array(
				'code' => 1,
				'msg'  => '用户名已存在!',
				'data' => '用户名已存在',
			);
		}
		// 手机正则匹配
		$phone = preg_match('/^0?(13|14|15|17|18|19)[0-9]{9}$/', $data['Phone']);
		if ($phone != 1) {
			return array(
				'code' => 1,
				'msg'  => '手机号码格式不正确!',
				'data' => '手机号码格式不正确',
			);
		}
		// 手机号码查重
		$isPhone = $model->getByPhone($data['Phone']);
		if ($isPhone != null) {
			return array(
				'code' => 1,
				'msg'  => '手机号码已注册!',
				'data' => '手机号码已注册',
			);
		}

		// 教师注册时，教师资格证不能为空
		if($data['Occupation'] == 2 && isset($data['Certification']) == false){
			return array(
				'code' => 1,
				'msg'  => '教师注册，需拥有有教师资格证',
				'data' => '教师注册，需拥有有教师资格证',
			);
		}

		// 写入数据库
		$id = $model->insertOne($data);
		//	添加笔记分类
		$mn=new ModelNoteCategory();
		$data=array(
			"Name"=>"爱笔记",
			"UserId"=>$id,
			"Intro"=>"如果你是一个好学生，你应该有分类名明确的很多笔记！"
		);
		$mn->addCategory($data);

		// 添加错题分类
		$mn=new ModelMistakeCategory();
		$data=array(
			"Name"=>"爱错题",
			"UserId"=>$id,
			"Intro"=>"错题当然可以使你进步啦！"
		);
		$mn->addCategory($data);

		if ($id<=0) {
			return array(
				'code' => 1,
				'msg'  => '注册操作异常!',
				'data' => '注册操作异常',
			);
		}
		return array(
			'code' => 0,
			'msg'  => '用户注册成功',
			'data' => $model->getUserById($id),
		);
	}

	/**
	 * 通过用户名获取用户信息
	 * @param name 用户名
	 */
	public function getUserByName($name)
	{
		$model = new Model();
		// 检查用户名
		$sql = $model->getByName($name);
		if (!$sql) {
			return array(
				'code' => 1,
				'msg'  => '用户名不存在!',
				'data' => '',
			);
		}
		return array(
			'code' => 0,
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
	public function getOnlineByToken()
	{
		$tokenModel = new Token();
		$count = count($tokenModel->getAll());
		return $count;
	}

	/**
	 * 获取学生数量
	 * @param type 用户类型：1学生，2教师
	 */
	public function getCount($type = 1){
		$model = new Model();
		$count = $model -> getCount($type);
		return $count;
	}

	/**
	 * 根据用户类型获取用户列表
	 * @param type 用户类型：1学生，2教师，默认1
	 * @param page 当前页
	 * @param num  每页数量
	 */
	public function getList($type = 1, $page = 1, $num = 10){
		$model = new Model();
		$begin = ($page - 1) * $num;
		$user = $model -> getUserByOcc($type, $begin, $num);
		if(!$user){
			return "暂时没有该类型的用户";
		}
		return $user;
	}

	public function delete($str){
		$model = new Model();
		$Ids = explode(',', $str);
		$count = count($Ids);
		for($i = 0; $i < $count; $i++){
			$res = $model -> deleteOne($Ids[$i]);
		}
	  return 0;
	}


	/* --------------  时光  ------------- */

	/**
	 * 用户主页推荐
	 * 根据用户最近30天的行为记录推荐题目(默认5道)，和最近三条笔记内容。
	 */

	public function getIndexRecommend($uid,$notesNum=3,$qnum=6)
	{
	
		try{
			$dq=new Recommend();

			if($uid==0) 
			{
				$mq=new ModelQSearch();
				return $mq->getHotQuesion(5);
			}
		
			$questions=$dq->recommendByUId($uid,0,User::recommendDate,$qnum);
	
			if(count($questions)<=0)
			{
				$questions=QBasic::hotQuestion($qnum);
			}
		
			$mn=new ModelNote();
			$notes=$mn->getNotesByUserId($uid,$notesNum); //笔记
			return array("Notes"=>$notes,"Questions"=>$questions);
		}
		catch (Exception $e)
		{
			return array("Notes"=>[],"Questions"=>[]);
		}
	}

	public function updateUser($data)
	{
		$um=new Model();
		return $um->updateOne($data);
	}

	/**
	 *  通过用户id获取用户信息，可以存在请求者id
	 */
	public function getByUserId($id,$rid)
	{
		$um=new Model();
		$re=$um->getUserById($id);
		if($rid>0)
		{
			$mf=new ModelFocus();
			$re["Focus"]=$mf->judgeUserFocusUser($rid,$id);
			$re["Fans"]=$mf->judgeUserFocusUser($id,$rid);
		}
		return $re;
	}
}

