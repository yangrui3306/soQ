<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class School extends NotORM{


	/* -------------   数据库查询操作   --------------- */

	/**
	 * 通过学校名获取学校信息
	 */
	public function getByName($name){

		$model = $this -> getORM();
		return $model -> where('Name', $name) -> fetchOne();
	}

	public function getById($Id){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> fetchOne();
	}

	public function getAll(){
		$model = $this -> getORM();
		return $model -> fetchAll();
	}

	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('Id');
	}

	public function getList($begin, $num){
		$model = $this -> getORM();
		return $model -> limit($begin, $num) -> fetchAll();
	}

	/* -------------    数据库插入操作    -------------- */

	public function insertOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return $model -> insert_id();
	}

	/* -------------    数据库更新操作    -------------- */

	public function updateOne($Id, $data){
		$model = $this -> getORM();
		return $model -> where("Id", $Id) -> update($data);
	}

	/* -------------    数据库删除操作    -------------- */

	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('Id', $id) -> delete();
	}
}