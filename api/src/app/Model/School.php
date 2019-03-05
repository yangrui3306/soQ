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

	/* -------------    数据库插入操作    -------------- */

	public function insertOne($data){
		$model = $this -> getORM();
		return $model -> insert($data);
	}

	/* -------------    数据库更新操作    -------------- */

	public function updateOne($data){
		$model = $this -> getORM();
		return $model -> where("Id", $data['Id']) -> update($data);
	}

	/* -------------    数据库删除操作    -------------- */

	public function deleteOne($uid){
		$model = $this -> getORM();
		return $model -> where('Id', $uid) -> delete();
	}
}