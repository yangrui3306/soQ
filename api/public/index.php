<?php
/**
 * 统一访问入口
 */

require_once dirname(__FILE__) . '/init.php';
header('Access-Control-Allow-Origin:*');
$pai = new \PhalApi\PhalApi();


// 惰性加载Redis, 去掉注释
// \PhalApi\DI()->redis = function () {
//     return new \PhalApi\Redis\Lite(\PhalApi\DI()->config->get("app.redis.servers"));
// };

$pai->response()->output();