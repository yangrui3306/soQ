<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Loginlog extends NotORM{

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

	public function getByTime($time){
		$model = $this -> getORM();
		return $model -> where('Ctime < ?', $time) -> fetchAll();
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


	/* -----------------   数据库删除操作  -------------------- */
		
	public function deleteOne($id = 0){
		$model = $this -> getORM();
		$sql = $model -> where("Id", $id) -> delete();
	}
}