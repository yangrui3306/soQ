<?php
namespace App\Common;

use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

class SignFilter implements Filter{

	/**
	 * 检查签名信息
	 * @param sign 接入方签名
	 */
	public function check(){
		$allParam = \PhalApi\DI()->request->getAll(); // 获取接口所有参数
		$sign = \PhalApi\DI()->request->get('sign');
		
		// 排除签名参数
		$sign = isset($allParam['sign']) ? $allParam['sign'] : '';
		unset($allParam['sign']);
		unset($allParam['s']);
		unset($allParam['service']);

		// 将所有参数排序并接成字符串
		ksort($allParam);
    $paramsStrExceptSign = '';
    // foreach ($allParam as $val) {
    //     $paramsStrExceptSign .= $val; 
	// 	}仅仅将password与username结合
		$paramsStrExceptSign=$paramsStrExceptSign.$allParam["password"].$allParam['username'].date('Y-m-d', time());
		

		$str     = strtoupper('soq'.$paramsStrExceptSign.'soq');
		$pubSign = md5($str);
		
		// api端密匙
		$serverSign = md5('api_'.$pubSign.'_api');
		$userSign   = md5('api_'.$sign.'_api');

    if ($userSign != $serverSign) {
        throw new BadRequestException($str, 1);
    }
	}
}