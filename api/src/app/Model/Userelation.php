<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Userelation extends NotORM{

	protected function getTableName($id)
  {
    return 'userelation';
	}
	
	/* ----------------  数据库查询  ------------------ */

	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('Id');
	}

	public function getList($begin, $num){
		$model = $this -> getORM();
		return $model -> limit($begin, $num) -> fetchAll();
	}

	public function getById($Id){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> fetchOne();
	}

	public function getByTid($Tid,$begin,$num){
		$model = $this -> getORM();
		return $model -> where('Tid', $Tid)-> limit($begin, $num) -> fetchAll();
	}

	public function getByCid($Cid){
		$model = $this -> getORM();
		return $model -> where('Cid', $Cid) -> fetchAll();
	}

	/* ----------------  数据库插入  ------------------ */

	public function insertOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return  $model->insert_id();
	}

	/* ----------------  数据库更新  ------------------ */

	public function updateOne($Id, $data){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> update($data);
	} 
	
	
	/* ----------------  数据库删除  ------------------ */

	public function deleteOne($Id){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> delete();
	}
}