<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Manager extends NotORM{

	/* --------------   数据库查询操作   ---------------- */

	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('Id');
	}

	public function getById($id){
		$model = $this -> getORM();
		return $model -> where('Id', $id) -> fetchOne();
	}

	public function getList($begin, $num){
		$model = $this -> getORM();
		return $model -> limit($begin, $num) -> fetchAll();
	}

	public function getByName($name){
		$model = $this -> getORM();
		return $model -> where('Name', $name) -> fetchOne();
	}

	public function getByPhone($phone){
		$model = $this -> getORM();
		return $model -> where('Phone', $phone) -> fetchOne();
	}


	/* -----------------   数据库插入操作   ------------------- */

	/**
	 * 增加一条用户记录
	 * @param data 用户信息数组
	 * @return 插入Id
	 */
	public function insertOne($data){
		$model = $this -> getORM();
    return $model -> insert($data);;
	}

	/* -----------------   数据库更新操作  -------------------- */

	public function updateOne($id, $data){
		$model = $this -> getORM();
		$sql = $model -> where('Id', $id) -> update($data);
		return $sql;
	}

	/* -----------------   数据库删除操作  -------------------- */
		
	public function deleteOne($uid){
		$model = $this -> getORM();
		$sql = $model -> where("Id", $uid) -> delete();
	}
}