<?php
/**
 * 请在下面放置任何您需要的应用配置
 *
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

return array(
		
	/**
  * 应用接口层的统一参数
  */
 'apiCommonRules' => array(
     'sign' => array('name' => 'sign', 'require' => true),
 ),

    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     * @author ipso
     */
    'service_whitelist' => array(
				'Collection.*',
				'Focus.*',
				'Like.*',
				'Mistake.*',
				'Note.*',
				'QCategory.*',
				'School.*',
				'Site.*',
				'User.getCode',
				'User.add',
				'User.getUser',
				'User.getUid',
				'User.getRecommend',
				// 以命名空间名称为key
        // 'App' => array(
        //     'app.service_whitelist.{"App\Api\Question"}'
        // ),
    ),
);
