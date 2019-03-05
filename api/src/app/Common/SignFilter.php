<?php
namespace App\Common;

use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

class SignFilter implements Filter{

	/**
	 * 检查签名信息
	 * @param token 用户token
	 */
	public function check(){

		$signature = \PhalApi\DI()->request->get('signature');
    $timestamp = \PhalApi\DI()->request->get('timestamp');
		$nonce = \PhalApi\DI()->request->get('nonce');
		
		$token = new Token();
		$token = $token;

		$tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );

    if ($tmpStr != $signature) {
        throw new BadRequestException('wrong sign', 1);
    }
	}
}