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
	// /**
	//  * 七牛相关配置
	//  */
	// 'Qiniu' =>  array(
	// 	//统一的key
	// 	'access_key' => '9fohNAVr7vA0PPDVnnlX1qLB0sYldEja_h3aq4nw',
	// 	'secret_key' => 'iU5sAQ6SrVpLZ7p8f9aIUmf3umXw4fwvBTs87ibN',
	// 	//自定义配置的空间
	// 	'space_bucket' => 'goodtimp',
	// 	'space_host' => 'http://pocqqayls.bkt.clouddn.com', // 如果有配置此项，则优先使用此域名
	// 	'preffix' => 'vNote_', // 上传文件名前缀
	// ),
	
 /**
     * 云上传引擎,支持local,oss,upyun
     */
    'UCloudEngine' => 'local',

    /**
     * 本地存储相关配置（UCloudEngine为local时的配置）
     */
    'UCloud' => array(
        //对应的文件路径
        'host' => 'http://localhost/phalapi/public/upload'
    ),



	/**
	 * 接口服务白名单，格式：接口服务类名.接口服务方法名
	 * @author ipso
	 */
	'service_whitelist' => array(
		'Collection.*',
		'Question.*',
		'Focus.*',
		'Like.*',
		'Mistake.*',
		'Manager.*',
		'Note.*',
		'QCategory.*',
		'Recharge.*',
		'Loginlog.*',
		'School.*',
		'Site.*',
		'Question.*',
		'User.getCode',
		'User.add',
		'User.getUser',
		'User.getUid',
		'User.getRecommend',
		'User.getTest',
		'User.getByName',
		'User.getById',
		'Keyword.*',
		'Upload.*',
		'User.changeUserAvatar',
		'User.getStudents',
		'User.getStudentCount',
		'User.getTeachers',
		'User.getTeacherCount',
		'User.delete',
	),
);
