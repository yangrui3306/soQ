<?php
namespace App\Domain;

use App\Model\School as Model;

class School {

	/**
	 * 获取所有学校信息
	 */
	public function getAll(){
		$model = new Model();
		$school = $model -> getAll();
		return $school;
	}

	/**
	 * 获取学校列表
	 * @param page  当前页
	 * @param num   获取数量
	 */
	public function getList($page = 1, $num = 10){
		$model = new Model();
		$begin = ($page - 1) * $num;
		$list = $model -> getList($begin, $num);
		if(!$list){
			return 1;
		}
		return $list;
	}
	
	/**
	 * 获取学校数量
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		return $count;
	}

	/**
	 * 通过学校名称获取学校信息
	 */
	public function getByName($name = ''){
		$model = new Model();
		$school = $model -> getByName($name);
		if(!$school){
			return 1;
		}
		return $school;
	}

	/**
	 * 添加学校
	 */
	public function add($data){
		$model = new Model();
		// 查看学校是否已经存在
		$school = $model -> getByName($data['Name']);
		if($school){
			return array(
				'code' => 1,
				'msg'  => '学校已经被添加',
				'data' => '',
			);
		}

		// 存入数据库
		$sql = $model -> insertOne($data);
		if(!$sql){
			return array(
				'code' => 1,
				'msg'  => '添加失败',
				'data' => '',
			);
		}
		return array(
				'code' => 0,
				'msg'  => '添加成功',
				'data' => $sql,
			);
	}

	/**
	 * 根据Id更新学校信息
	 */
	public function update($data){
		$model = new Model();
		$Id = $data['Id'];
		unset($data['Id']);
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return 1;
		}
		return 0;
	}

	/**
	 * 删除一条或多条学校信息
	 */
	public function delete($strId){
		$model = new Model();
		$Ids = explode(',', $strId);
		$count = count($Ids);
		$flag = true;
		$isnot = '';
		for($i = 0; $i < $count; $i++){
			$res = $model -> deleteOne($Ids[$i]);
			if(!$res){
				$flag = false;
				$isnot = $Ids[$i];
				break;
			}
		}
		if($flag == false){
			return array(
				'code' => 1,
				'msg'  => '删除失败，'.$isnot.'不存在',
				'data' => '',
			);
		}
	  return array(
				'code' => 0,
				'msg'  => '删除成功',
				'data' => '',
			);
	}
}