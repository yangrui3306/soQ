<?php
/**
 * 统一访问入口
 */

require_once dirname(__FILE__) . '/init.php';
header('Access-Control-Allow-Origin:*');
$pai = new \PhalApi\PhalApi();
$pai->response()->output();

