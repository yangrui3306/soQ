<?php
namespace App\Api;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Domain\School as Domain;

class School extends Api{

	public function getRules(){
		return array(
			'getAll' => array(
			),
		);
	}

	/**
	 * 获取所有学校信息
	 */
	public function getAll(){
		$domain = new Domain();
		$schools = $domain -> getAll();
		return MyStandard::gReturn(0,$schools,'');
	}
}