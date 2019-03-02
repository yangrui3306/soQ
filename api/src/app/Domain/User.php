<?php
namespace App\Domain;

use App\Model\User as Model;

class User
{

	/**
	 * 获取用户性别
	 * @param  name 用户名
	 * @return sex  用户性别
	 */
	public function GetUserSexById($id){
		$model = new Model();
		$sex = $model -> getSex($id);
		return $sex;
	}
}