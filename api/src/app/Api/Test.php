<?php
namespace App\Api;
use App\Domain\Test as Domain;
use App\Model\Test as Model;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Domain\Notice as NoticeDomain;
use App\Model\User as UserModel;

/**
 * 测试类接口
 */
class Test extends Api {

	public function getRules(){
		return array(
			'add' => array(
				'State'  => array('name' => 'State', 'require' => true, 'default' => 0, 'desc' => '测试状态：0未开始，1已开始'),
				'TeacherId'  => array('name' => 'TeacherId', 'desc' => '教授Id'),
				'Title'  => array('name' => 'Title', 'desc' => '测试标题'),
				'CateId' => array('name' => 'CateId', 'desc' => '科目Id'),
				'LimiteTime' => array('name' => 'LimiteTime', 'desc' => '测试时长，单位分钟，整型'),
				'UserrelationId' => array('name' => 'UserrelationId', 'desc' => '班级Id'),
				'Content' => array('name' => 'Content', 'desc' => '题目Id，以英文逗号隔开的Id字符串'),
			),
			'getCount' => array(
				
			),
			'getList' => array(
				'Page'  => array('name' => 'Page',  'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '每页数量'),
			),
			'update' => array(
				'Id' => array('name' => 'Id', 'desc' => '通知Id'),
				'State'  => array('name' => 'State', 'require' => true, 'default' => 0, 'desc' => '测试状态：0未开始，1已开始'),
				'TeacherId'  => array('name' => 'TeacherId', 'desc' => '教授Id'),
				'CateId' => array('name' => 'CateId', 'desc' => '科目Id'),
				'LimiteTime' => array('name' => 'LimiteTime', 'desc' => '测试时长，单位分钟，整型'),
				'UserrelationId' => array('name' => 'UserrelationId', 'desc' => '班级Id'),
				'Content' => array('name' => 'Content', 'desc' => '题目Id，以英文逗号隔开的Id字符串'),
				'Title'  => array('name' => 'Title', 'desc' => '测试标题'),
			),
			'delete' => array(
				'Id'  => array('name' => 'Id', 'require' => true, 'desc' => '要删除记录的Id'),
			),
			'getAllByTeacher' => array(
				'Tid' => array('name' => 'Tid', 'require' => true, 'desc' => '教师Id'),
				'Page'  => array('name' => 'Page',  'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '每页数量'),
			),
		);
	}

	/**
	 * 添加一条测试信息
	 */
	public function add(){
		$data = array(
			'State'          => $this -> State,
			'Title'          => $this -> Title,
			'TeacherId'      => $this -> TeacherId,
			'CateId'         => $this -> CateId,
			'LimiteTime'     => $this -> LimiteTime,
			'UserrelationId' => $this -> UserrelationId,
			'Content'        => $this -> Content,
			'Ctime'          => time(),
		);
		$model = new Model();
		$sql = $model -> insertOne($data);
		if(!$sql){
			return MyStandard::gReturn(1, '', '添加失败');
		}
		return MyStandard::gReturn(0, $sql, '添加成功');
	}

	/**
	 * 更新一条测试记录， 更改测试状态也属于更新测试记录
	 */
	public function update(){
		$data = array(
			'State'          => $this -> State,
			'TeacherId'      => $this -> TeacherId,
			'CateId'         => $this -> CateId,
			'LimiteTime'     => $this -> LimiteTime * 60,
			'UserrelationId' => $this -> UserrelationId,
			'Title'          => $this -> Title,
			'Content'        => $this -> Content,
			'Ctime'          => time(),
		);
		$Id = $this -> Id;
		$model = new Model();
		$isId = $model -> getById($Id);
		if(!$isId){
			return MyStandard::gReturn(1, '', '记录不存在, 更新失败');
		}

		// 如果更改了测试状态，则新建一条通知
		if($data['State'] == 1){
			$noticeDomain = new NoticeDomain();
			$userModel = new UserModel();
			$teacher = $userModel -> getUserById($data['TeacherId']);
			$notice = array(
				'Status'   => 0,
				'Title'    => '发布测试',
				'Content'  => '发布新测试，请同学们积极参与',
				'Author'   => $teacher['Name'],
				'Ctime'    => date('Y-m-d H:i:s'),
				'AcceptId' => $data['UserrelationId'],
			);
			$noticeSql = $noticeDomain -> add($notice);
		}

		// 更新测试
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return MyStandard::gReturn(1, '', '更新失败');
		}
		return MyStandard::gReturn(0, '', '更新成功');
	}

	/**
	 * 获取测试数量
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		return MyStandard::gReturn(0, $count, '获取成功');
	}

	/**
	 * 获取测试列表
	 */
	public function getList(){
		$begin = ($this -> Page - 1) * $this -> Number;
		$model = new Model();
		$num = $this -> Number;
		$list = $model -> getList($begin, $num);
		if(!$list){
			return MyStandard::gReturn(1, '', '获取失败');
		}
		return MyStandard::gReturn(0, $list, '获取成功');
	}

	/**
	 * 通过教师id查找测试列表
	 */
	public function getAllByTeacher(){
		$page = $this -> Page;
		$num = $this -> Number;
		$teacher = $this -> Tid;
		$begin = ($page - 1) * $num;
		$model = new Model();
		$list = $model -> getByTid($teacher, $begin, $num);
		if(!$list){
			return MyStandard::gReturn(1, '', '获取失败');
		}
		return MyStandard::gReturn(0, $list, '获取成功');
	}
}