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

	/**
	 * 通过ID获取一张试题
	 */
	public function getById($ID){
		$model = $this -> getORM();
		return $model -> where('Id', $ID) -> fetchOne();
	}

	/* -----------------    数据库插入     ------------------ */

	public function insertOne($data){
		$model = $this -> getORM();
	}

	/* -----------------    数据库更新     ------------------ */



	/* -----------------    数据库删除     ------------------ */
}