<?php
namespace App\Domain;
use App\Model\Notice as Model;

class Notice {
	
	public function add($data){
		$model = new Model();
		$sql = $model -> insertOne($data);
		return $sql;
	}
}