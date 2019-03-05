<?php
namespace App\Domain;

use App\Model\School as Model;

class School {

	/**
	 * 获取所有学校信息
	 */
	public function getAll(){
		$model = new Model();
		$school = $model -> getAll();
		return $school;
	}
	
}