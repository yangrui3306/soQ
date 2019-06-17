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
	public function getByUid($uid,$begin,$num){
		$sql = "SELECT * FROM userelation WHERE Sid like ? ORDER BY Ctime DESC limit ?,?";
		$params = array("%,".$uid.",%",intval($begin),intval($num));
		return $this->getORM()->queryRows($sql, $params);
	}
	public function getByCid($Cid){
		$model = $this -> getORM();
		return $model -> where('Cid', $Cid) -> fetchAll();
	}

	/* ----------------  数据库插入  ------------------ */

	public function insertOne($data){
		$data['Ctime']=date("Y-m-d H:i:s");
		$model = $this -> getORM();
		$model -> insert($data);
		return  $model->insert_id();
	}
	public function addSid($id,$sid){
		$sql="update userelation set Sid=CONCAT(Sid,?) where Id=?";
		$params = array($sid.",",$id);
		return $this->getORM()->queryRows($sql, $params);
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