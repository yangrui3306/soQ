<?php
namespace App\Api;
use App\Domain\Userelation as Domain;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Model\Userelation as Model;

/**
 * 班级类接口
 */
class Userelation extends Api{
	
	public function getRules(){
		return array(
			'add' => array(
				'Tid'  => array('name' => 'Tid', 'require' => true, 'desc' => '教师Id，必须'),
				'Sid'  => array('name' => 'Sid', 'desc' => '学生Id的字符串形式，英文逗号隔开'),
				'Cid' => array('name' => 'Cid', 'desc' => '科目Id'),
				'Endtime' => array('name' => 'Endtime', 'desc' => '班级结束时间'),
				'Intor' => array('name' => 'Intor', 'desc' => '简介'),
			),
			'getCount' => array(
				
			),
			'getList' => array(
				'Page'  => array('name' => 'Page',  'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '每页数量'),
			),
			'update' => array(
				'Id' => array('name' => 'Id', 'desc' => '班级Id'),
				'Tid'  => array('name' => 'Tid', 'require' => true, 'desc' => '教师Id，必须'),
				'Sid'  => array('name' => 'Sid', 'desc' => '学生Id的字符串形式，英文逗号隔开'),
				'Cid' => array('name' => 'Cid', 'desc' => '科目Id'),
				'Endtime' => array('name' => 'Endtime', 'desc' => '班级结束时间'),
				'Intor' => array('name' => 'Intor', 'desc' => '简介'),
			),
			'delete' => array(
				'Id'  => array('name' => 'Id', 'require' => true, 'desc' => '要删除记录的Id'),
			),
		);
	}

	/**
	 * 添加一条记录
	 */
	public function add(){
		$data = array(
			'Tid'     => $this -> Tid,
			'Sid'     => $this -> Sid,
			'Cid'     => $this -> Cid,
			'Endtime' => $this -> Endtime,
			'Intor'   => $this -> Intor,
			'Ctime'   => date('Y-m-d H:i:s'),
		);

		$model = new Model();
		$sql = $model -> insertOne($data);
		if(!$sql){
			return MyStandard::gReturn(1, '', '添加失败');
		}
		return MyStandard::gReturn(0, $sql, '添加成功');
	}

	/**
	 * 更新一条记录， 新增学生也属于更新记录
	 */
	public function update(){
		$data = array(
			'Tid'     => $this -> Tid,
			'Sid'     => $this -> Sid,
			'Cid'     => $this -> Cid,
			'Endtime' => $this -> Endtime,
			'Intor'   => $this -> Intor,
			'Ctime'   => date('Y-m-d H:i:s'),
		);
		$Id = $this -> Id;
		$model = new Model();
		$isId = $model -> getById($Id);
		if(!$isId){
			return MyStandard::gReturn(1, '', '更新失败，记录不存在');
		}
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
}
