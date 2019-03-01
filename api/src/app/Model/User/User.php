<?php
namespace App\Model;
use PhalApi\Model\NotORMModel as NotORM;

/**
 * 用户表
 * @author ipso
 */
class User extends NotORM{
	public function getAll(){
		$model = $this->NotORM();
		return $model -> fetchAll();
	}
}