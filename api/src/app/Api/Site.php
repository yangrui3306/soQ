<?php
namespace App\Api;
use PhalApi\Api;
use App\Common\Match as Match;

/**
 * 默认接口服务类
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Site extends Api {
    public function getRules() {
        return array(
            'index' => array(
                'username'  => array('name' => 'username', 'default' => 'PhalApi', 'desc' => '用户名'),
            ),
        );
    }

    /**
     * 默认接口服务
     * @desc 默认接口服务，当未指定接口服务时执行此接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     * @exception 400 非法请求，参数传递错误
     */
    public function index() {
        $a=new Match();
        return array(
            'title' => $a->levenshtein('把向东运动记做“+”，向西运动记做“-”，下列士大夫说法正确的是','如果把向东运动地方记做“+”，如果向西记做“-”，下列说法正确的是')            
        );
    }
}
