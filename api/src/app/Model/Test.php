<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Test extends NotORM{

	/* -----------------    数据库查询     ------------------ */

	/**
	 * 获取测试试卷数量
	 */
	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('Id');
	}

	public function getById($ID){
		$model = $this -> getORM();
		return $model -> where('Id', $ID) -> fetchOne();
	}

	public function getList($begin, $num){
		$model = $this -> getORM();
		return $model -> limit($begin, $num) -> fetchAll();
	}

	public function getByTid($tid, $begin = 0, $num = 10){
		$model = $this -> getORM();
		return $model -> where('TeacherId', $tid) -> limit($begin, $num) -> fetchAll();
	}

	/* -----------------    数据库插入     ------------------ */

	public function insertOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return $model -> insert_id();
	}

	/* -----------------    数据库更新     ------------------ */

	public function updateOne($id, $data){
		$model = $this -> getORM();
		return $model -> where('Id', $id) -> update($data);
	}


	/* -----------------    数据库删除     ------------------ */

	public function deleteOne($Id){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> delete();
	}
}