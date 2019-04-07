<?php
namespace App\Api;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Domain\School as Domain;
/**
 * 学校部分
 */
class School extends Api{

	public function getRules(){
		return array(
			'getAll' => array(
			),
			'getCount' => array(
			),
			'getList' => array(
				'Page' => array('name' => 'Page', 'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '获取数量'),
			),
			'getByName' => array(
				'Name' => array('name' => 'Name', 'require' => true, 'desc' => '当前页'),
			),
			'add' => array( // Province
				'Name' => array('name' => 'Name', 'require' => true, 'desc' => '当前页'),
				'Province' => array('name' => 'Province', 'desc' => '学校所在省份'),
				'City' => array('name' => 'City', 'desc' => '学校所在城市'),
			),
			'update' => array( // Province
				'Id' => array('name' => 'Id', 'require' => true, 'desc' => '所要删除Id字符串'),
				'Name' => array('name' => 'Name', 'desc' => '当前页'),
				'Province' => array('name' => 'Province', 'desc' => '学校所在省份'),
				'City' => array('name' => 'City', 'desc' => '学校所在城市'),
			),
			'delete' => array( // Province
				'Id' => array('name' => 'Id', 'require' => true, 'desc' => '所要删除Id字符串'),
			),
		);
	}

	/**
	 * 获取所有学校信息
	 */
	public function getAll(){
		$domain = new Domain();
		$schools = $domain -> getAll();
		return MyStandard::gReturn(0,$schools,'');
	}

	/**
	 * 获取学校数量
	 */
	public function getCount(){
		$domain = new Domain();
		$count = $domain -> getCount();
		return MyStandard::gReturn(0,$count,'');
	}

	/**
	 * 获取学校列表
	 */
	public function getList(){
		$domain = new Domain();
		$page = $this -> Page;
		$num  = $this -> Number;
		$list = $domain -> getList($page, $num);
		if($list == 1){
			return MyStandard::gReturn(1, '', '获取失败');
		}
		return MyStandard::gReturn(0, $list);
	}

	/**
	 * 通过学校名称获取学校信息
	 */
	public function getByName(){
		$domain = new Domain();
		$name = $this -> Name;
		$school = $domain -> getByName($name);
		if($school == 1){
			return MyStandard::gReturn(1, '', '获取失败');
		}
		return MyStandard::gReturn(0, $school);
	}

	/**
	 * 添加学校
	 */
	public function add(){
		$domain = new Domain();
		$data = array(
			'Name'     => $this -> Name,
			'Province' => $this -> Province,
			'City'     => $this -> City,
		);
		$res = $domain -> add($data);
		if($res['code'] == 1){
			return MyStandard::gReturn(1, '', $res['msg']);
		}
		return MyStandard::gReturn(0, $res['data'], $res['msg']);
	}

	/**
	 * 更新学校Id更新学校信息
	 */
	public function update(){
		$domain = new Domain();
		$data = array(
			'Id'       => $this -> Id,
			'Name'     => $this -> Name,
			'Province' => $this -> Province,
			'City'     => $this -> City,
		);
		$res = $domain -> update($data);
		if($res == 1){
			return MyStandard::gReturn(1, '', '更新失败');
		}
		return MyStandard::gReturn(0, '', '更新成功');
	}

	/**
	 * 删除一个或多个学校
	 * @param Id 包含需要删除的学校Id的字符串，每个id之间用英文逗号隔开
	 */
	public function delete(){
		$strId = $this -> Id;
		$domain = new Domain();
		$res = $domain -> delete($strId);
		if($res['code'] == 1){
			return MyStandard::gReturn(1, '', $res['msg']);
		}
		return MyStandard::gReturn(0, '', $res['msg']);
	}
}