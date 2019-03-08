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
		$allParams = \PhalApi\DI()->request->getAll();
		$sign = \PhalApi\DI()->request->get('sign');
		

		$sign = isset($allParams['sign']) ? $allParams['sign'] : '';
    unset($allParams['sign']);

		// 将所有参数排序并接成字符串
		ksort($params);
    $paramsStrExceptSign = '';
    foreach ($params as $val) {
        $paramsStrExceptSign .= $val; 
		}
		$str = strtoupper('soq'.$paramsStrExceptSign.'soq');
		$pubSign = md5($str);
		// 
		
    $serverSign = md5($str);

    if ($sign != $serverSign) {
        throw new BadRequestException('签名失败', 1);
    }
	}
}