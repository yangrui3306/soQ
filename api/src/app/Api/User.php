<?php
namespace App\Api;
use App\Domain\User as Domain;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Common\GD;
use App\Domain\Question\GeneratieTest as ModelQGeneratie;
use PhalApi\Exception;

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
			'getByName' => array(
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
			),
			'getTest'=>array(
				'UserId' => array('name' => 'UserId', 'require' => true, 'desc' => "用户id"),
				'CategoryId' => array('name' => 'CategoryId','require' => true ,'type' => 'int',  'desc' => '题目分类id'),
				'Date'=>array('name'=>'Date','default'=>30 ,'desc'=>'根据近Date天生成，默认30'),
				'Number'=>array('name'=>'Number','default'=>10,'type'=>'int','desc'=>'生成的题目,默认10')
			),
			'getById'=>array(
				'UserId' => array('name' => 'UserId', 'require' => true, 'desc' => "用户id"),
				'RequesterId'=>array('name' => 'RequesterId', 'default' => 0,  'desc' => "请求者id")
			),
			'changeUserAvatar'=>array(
				'file' => array('name' => 'file','type' => 'file',
					'min' => 0,
					'max' => 1024 * 1024,
					'range' => array('image/jpg', 'image/jpeg', 'image/png'),
					'ext' => array('jpg', 'jpeg', 'png'),),
				'UserId'=>array('name' => 'UserId', 'require' => true, 'desc' => "用户id")
			)
		);
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
   * 通过用户id获取用户信息，可以存在请求者id
	 * 
   * @desc 通过用户id获取用户信息
   */
	public function getById(){
		$UserId = $this -> UserId;
		$domain = new Domain();
		
		$res = $domain -> getByUserId($UserId,$this->RequesterId);
		
		if(array_key_exists("Password",$res)) unset($res["Password"]);
		return MyStandard::gReturn(0,$res);
	}
	 /**
   * 通过用户名获取用户信息
   * @desc 通过用户名获取用户信息
   */
	public function getByName(){
		$name = $this -> Name;
		$domain = new Domain();
		$returnRule = new MyStandard();
		$res = $domain -> getUserByName($name);
		return $res;
		$this->unsetUserPassword($res["data"]);
	
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
	/** 
  * 得到测试题目
  */
  public function getTest()
  {
		$mqg=new ModelQGeneratie();

		$re=$mqg->getTest($this->UserId,$this->CategoryId,$this->Date,$this->Number);
		return MyStandard::gReturn(0,$re);
	}
	/**
	 * 修改用户头像
	 */
	public function changeUserAvatar(){

		$data=array(
			"Id"=>$this->UserId,
		);

		//设置上传路径 设置方法参考3.2
		\PhalApi\DI()->ucloud->set('save_path', date('Y/m/d'));
		$name = rand(213123, 1321321);
		//新增修改文件名设置上传的文件名称
		\PhalApi\DI()->ucloud->set('file_name', $name);

		//上传表单名
		$rs = \PhalApi\DI()->ucloud->upfile($this->file);
		try {
				$rs["errno"] = 0;
				$rs["data"] = [];
				$rs["data"][0] = "http://1975386453.38haotyhn.duihuanche.com/upload/". $rs["file"];
				unset($rs["file"]);
				$data["Avatar"]=	$rs["data"][0]; //修改地址
				$domain=new Domain();
				$re=$domain->updateUser($data);//更新

				return MyStandard::gReturn(0, $rs);
		} catch (Exception $e) {
				$rs["data"] = [];
				$rs["errno"] = 1;
				$rs["data"] = [];
				return MyStandard::gReturn(1, $rs);
		}
	}



	private function unsetUserPassword(&$arr)
	{
		try{
			for($i=0;$i<count($arr);$i++)
			{
				if(is_array($arr[$i]) && array_key_exists("Password",$arr[$i])) unset($arr[$i]["Password"]);
			}
		}
		catch(Exception $e)
		{
			return [];
		}
	}
} 