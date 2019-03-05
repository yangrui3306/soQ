<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class School extends NotORM{

	/**
	 * 通过学校名获取学校信息
	 */
	public function getByName($name){
		
		$model = $this -> getORM();
		return $model -> where('Name', $name) -> fetchOne();
	}
}