<?php
namespace App\Api;
use App\Domain\Test as Domain;
use App\Model\Test as Model;
use PhalApi\Api;
use App\Common\MyStandard;


/**
 * 测试类接口
 */
class Test extends Api {

	public function getRules(){
		return array(
			'add' => array(
				'Status'  => array('name' => 'Status',  'default' => 0, 'desc' => '测试状态：0未开始，1已开始'),
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
				'Page'  => array('name' => 'Page', 'default' => 1, 'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'default' => 5,'desc' => '每页数量'),
			),
			'update' => array(
				'Id' => array('name' => 'Id', 'require' => true,'desc' => '通知Id'),
				'Status'  => array('name' => 'Status',  'default' => 0, 'desc' => '测试状态：0未开始，1已开始'),
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
				'Page'  => array('name' => 'Page',  'default' => 1, 'desc' => '当前页'),
				'Number' => array('name' => 'Number','default' => 5,  'desc' => '每页数量'),
			),
			'getByTidRid' => array(
				'TeacherId' => array('name' => 'TeacherId', 'require' => true, 'desc' => '教师Id'),
				'UserrelationId' => array('name' => 'UserrelationId', 'require' => true, 'desc' => '班级Id'),
			),
			'getTestDetail'=>array(
				'Id'  => array('name' => 'Id', 'require' => true, 'desc' => '测试Id'),
			)
		);
	}

	/**
	 * 添加一条测试信息
	 */
	public function add(){
		$data = array(
			'Status'          => $this -> Status,
			'Title'          => $this -> Title,
			'TeacherId'      => $this -> TeacherId,
			'CateId'         => $this -> CateId,
			'LimiteTime'     => $this -> LimiteTime*60,
			'UserrelationId' => $this -> UserrelationId,
			'Content'        => $this -> Content,
			'Ctime'          => time(),
		);
	
		$domain= new Domain();
		$re=$domain->add($data);
	
		
		if(!$re){
			return MyStandard::gReturn(1, '', '添加失败');
		}
		return MyStandard::gReturn(0, $re, '添加成功');
	}

	/**
	 * 更新一条测试记录， 更改测试状态也属于更新测试记录
	 */
	public function update(){
		$data = array();
		$Id = $this -> Id;
		if($this->CateId!=null) $data["CateId"]=$this -> CateId;
		if($this->Status!=null) $data["Status"]=$this -> Status;
		if($this->TeacherId) $data["TeacherId"]=$this->TeacherId;
		if($this->LimiteTime) $data["LimiteTime"]= $this -> LimiteTime * 60;
		if($this->Title) $data["Title"]=$this->Title;
		if($this->Content) $data["Content"]=$this->Content;
		$data["Ctime"]= time();

		$domain = new Domain();
		// 更新测试
		$re = $domain -> update($Id, $data);

		if($re==-1) return MyStandard::gReturn(1, 0, '更新记录不存在，更新失败');

		if($re==0){
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
			return MyStandard::gReturn(1, [], '获取失败');
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
			return MyStandard::gReturn(1, [], '无数据');
		}
		return MyStandard::gReturn(0, $list, '获取成功');
	}
	/**
	 * 根据老师Id和班级Id得到测试信息
	 */
	public function getByTidRid(){
		$UserrelationId=$this->UserrelationId;
		$TeacherId=$this->TeacherId;
		$model = new Model();
		$list = $model -> getByTidRid($TeacherId, $UserrelationId);
		if(!$list){
			return MyStandard::gReturn(1, [], '无数据');
		}
		return MyStandard::gReturn(0, $list, '获取成功');
	}
	/**
	 * 得到测试详情信息
	 */
	public function getTestDetail(){
	
		$domain = new Domain();
		$re=$domain->getByTestDetail($this->Id);
		if(!$re){
			return MyStandard::gReturn(1, [], '无数据');
		}
		return MyStandard::gReturn(0, $re, '获取成功');
	}
}