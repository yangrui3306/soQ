<?php
 /*
 * +----------------------------------------------------------------------
 * | 上传接口
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 summer All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: summer <aer_c@qq.com> <qq7579476>
 * +----------------------------------------------------------------------
 * | This is not a free software, unauthorized no use and dissemination.
 * +----------------------------------------------------------------------
 * | Date
 * +----------------------------------------------------------------------
 */
namespace App\Api;

use PhalApi\Api;
use App\Common\MyStandard;
use PhalApi\Exception;

/**
 * 文件上传 
 */
class Upload extends Api
{

    /**
     * 获取参数
     * @return array 参数信息
     */
    public function getRules()
    {
        return array(
            'upload' => array(
                'file' => array(
                    'name' => 'file',
                    'type' => 'file',
                    'min' => 0,
                    'max' => 1024 * 1024 * 1024 * 60,
                    'range' => array('image/jpg', 'image/jpeg', 'image/png'),
                    'ext' => array('jpg', 'jpeg', 'png')
                ),
            ),
        );
    }

    /**
     * 上传文件
     * @return string $url 绝对路径
     * @return string $file 相对路径，用于保存至数据库，按项目情况自己决定吧
     */
    public function upload()
    {
        //设置上传路径 设置方法参考3.2
        \PhalApi\DI()->ucloud->set('save_path', date('Y/m/d'));
        $name = rand(213123, 1321321);
        //新增修改文件名设置上传的文件名称
				\PhalApi\DI()->ucloud->set('file_name', $name . $_SERVER['REQUEST_TIME']);
				
        $rs = \PhalApi\DI()->ucloud->upfile($this->file);
        try {
            $rs["errno"] = 0;
            $rs["data"] = [];
            $rs["data"][0] = "http://1975386453.38haotyhn.duihuanche.com/upload/".$rs["file"];
            unset($rs["file"]);
         
            return MyStandard::gReturn(0, $rs);
        } catch (Exception $e) {
            $rs["data"] = [];
            $rs["errno"] = 1;
            $rs["data"] = [];
            return MyStandard::gReturn(1, $rs);
        }
    }
}
 